<?php

	/** Класс подписчика на рассылку */
	class umiSubscriber extends umiObject implements iUmiSubscriber {

		/** @var bool|iUmiObject $o_user объект пользователя */
		protected $o_user;

		/**
		 * @inheritDoc
		 * @param $id
		 * @param bool|array $row
		 * @throws coreException
		 * @throws privateException
		 * @throws selectorException
		 */
		public function __construct($id, $row = false) {
			$umiObjects = umiObjectsCollection::getInstance();
			$umiObjectTypes = umiObjectTypesCollection::getInstance();
			$umiHierarchyTypes = umiHierarchyTypesCollection::getInstance();

			$this->store_type = 'subscriber';
			$object = $umiObjects->getObject($id);

			if ($object instanceof iUmiObject) {
				$typeId = $object->getTypeId();
				$type = $umiObjectTypes->getType($typeId);
				$hierarchyType = $umiHierarchyTypes->getType($type->getHierarchyTypeId());

				if ($hierarchyType->getName() === 'dispatches' && $hierarchyType->getExt() === 'subscriber') {
					$uid = $object->getValue('uid');
					$this->o_user = $umiObjects->getObject($uid);
				} elseif ($hierarchyType->getName() === 'users' && $hierarchyType->getExt() === 'user') {
					$this->o_user = $object;
					$id = self::getSubscriberByUserId($id);
				}
			}

			parent::__construct($id);
		}

		/** @inheritDoc */
		public function isRegisteredUser() {
			return ($this->o_user instanceof iUmiObject);
		}

		/** @inheritDoc */
		public function getDispatches() {
			return $this->getValue('subscriber_dispatches');
		}

		/** @inheritDoc */
		public function releaseWasSent($id) {
			return in_array($id, $this->getSentReleaseIdList());
		}

		/** @inheritDoc */
		public function getSentReleaseIdList() {
			return (array) $this->getValue('sent_release_list');
		}

		/** @inheritDoc */
		public function putReleaseToSentList($id) {
			$sentReleaseIdList = $this->getSentReleaseIdList();
			$sentReleaseIdList[] = $id;
			$sentReleaseIdList = array_unique($sentReleaseIdList);
			$this->setValue('sent_release_list', $sentReleaseIdList);
			return $this;
		}

		/** @inheritDoc */
		public function getFullName() {
			$namePartList = [
				(string) $this->getValue('lname'),
				(string) $this->getValue('fname'),
				(string) $this->getValue('father_name')
			];
			return implode(' ', $namePartList);
		}

		/** @inheritDoc */
		public function getEmail() {
			$email = (string) $this->getValue('email');

			if ($email === '') {
				$email = (string) $this->getName();
			}

			return $email;
		}

		/** @inheritDoc */
		public static function getSubscriberByUserId($userId) {
			$umiObjects = umiObjectsCollection::getInstance();

			$type = selector::get('object-type')->name('dispatches', 'subscriber');
			$typeId = $type->getId();

			$sel = new selector('objects');
			$sel->types('object-type')->id($typeId);
			$sel->where('uid')->equals($userId);
			$sel->limit(0, 1);

			/** @var umiObject|null $subscriber */
			$subscriber = $sel->first();

			if ($subscriber) {
				$subscriberId = $subscriber->getId();
			} else {
				$user = $umiObjects->getObject($userId);

				$email = $user->getValue('e-mail');
				$lastName = $user->getValue('lname');
				$firstName = $user->getValue('fname');
				$middleName = $user->getValue('father_name');
				$gender = $user->getValue('gender');

				$subscriberId = $umiObjects->addObject($email, $typeId);
				$subscriber = $umiObjects->getObject($subscriberId);

				if ($subscriber instanceof iUmiObject) {
					$subscriber->setName($email);
					$subscriber->setValue('lname', $lastName);
					$subscriber->setValue('fname', $firstName);
					$subscriber->setValue('father_name', $middleName);

					$date = new umiDate(time());
					$subscriber->setValue('subscribe_date', $date);
					$subscriber->setValue('gender', $gender);
					$subscriber->setValue('uid', $userId);
				}

				$subscriber->commit();
			}

			return $subscriberId;
		}

		/** @deprecated */
		public function isRegistredUser() {
			return $this->isRegisteredUser();
		}
	}


