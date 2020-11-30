<?php

	namespace UmiCms\Classes\System\Utils\Links\Grabber;

	/**
	 * Class State состояние сбора ссылок
	 * @package UmiCms\Classes\System\Utils\Links\Grabber
	 */
	class State implements iState {

		/** @var array $statesOfSteps состояния шагов сбора */
		private $statesOfSteps;

		/** @var string $currentStepName имя текущего шага сбора */
		private $currentStepName;

		/** @var bool $isComplete завершен ли сбор */
		private $isComplete;

		/** @inheritDoc */
		public function __construct(array $state) {
			if (!isset($state[iState::STEPS_KEY])) {
				throw new \wrongParamException('Cant detect current step');
			}

			$stepsState = $state[iState::STEPS_KEY];

			if (!isset($state[iState::CURRENT_STEP_KEY])) {
				throw new \wrongParamException('Cant detect current step');
			}

			$currentStepName = $state[iState::CURRENT_STEP_KEY];

			if (!isset($state[iState::COMPLETE_KEY])) {
				throw new \wrongParamException('Cant detect complete status');
			}

			$completeStatus = $state[iState::COMPLETE_KEY];

			$this->setCurrentStepName($currentStepName)
				->setStepsState($stepsState)
				->setCompleteStatus($completeStatus);
		}

		/** @inheritDoc */
		public function export() {
			return [
				iState::CURRENT_STEP_KEY => (string) $this->getCurrentStepName(),
				iState::STEPS_KEY => (array) $this->getStatesOfSteps(),
				iState::COMPLETE_KEY => (bool) $this->isComplete(),
			];
		}

		/** @inheritDoc */
		public function setCurrentStepName($stepName) {
			if (!is_string($stepName) || $stepName === '') {
				throw new \wrongParamException('Wrong step name given');
			}
			$this->currentStepName = $stepName;
			return $this;
		}

		/** @inheritDoc */
		public function setStepsState($statesOfSteps) {
			if (!is_array($statesOfSteps) || umiCount($statesOfSteps) === 0) {
				throw new \wrongParamException('Wrong states of steps given');
			}
			$this->statesOfSteps = $statesOfSteps;
			return $this;
		}

		/** @inheritDoc */
		public function isComplete() {
			if ($this->isComplete === null) {
				throw new \wrongParamException('You should set is complete status first');
			}

			return $this->isComplete;
		}

		/** @inheritDoc */
		public function getCurrentStepName() {
			if ($this->currentStepName === null) {
				throw new \wrongParamException('You should set current step name first');
			}

			return $this->currentStepName;
		}

		/** @inheritDoc */
		public function getStepsNames() {
			if ($this->statesOfSteps === null) {
				throw new \wrongParamException('You should set steps state first');
			}

			return array_keys($this->statesOfSteps);
		}

		/** @inheritDoc */
		public function getStatesOfSteps() {
			if ($this->statesOfSteps === null) {
				throw new \wrongParamException('You should set steps state first');
			}

			return $this->statesOfSteps;
		}

		/** @inheritDoc */
		public function getStateOfStep(Steps\iStep $step) {
			if ($this->statesOfSteps === null) {
				throw new \wrongParamException('You should set steps state first');
			}

			$stepName = $step->getName();

			if (!isset($this->statesOfSteps[$stepName])) {
				throw new \wrongParamException('Unsupported step given');
			}

			return $this->statesOfSteps[$stepName];
		}

		/** @inheritDoc */
		public function setStateOfStep(Steps\iStep $step) {
			if ($this->statesOfSteps === null) {
				$this->statesOfSteps = [];
			}

			$this->statesOfSteps[$step->getName()] = $step->getState();
			return $this;
		}

		/** @inheritDoc */
		public function setCompleteStatus($completeStatus) {
			if (!is_bool($completeStatus)) {
				throw new \wrongParamException('Wrong complete status given');
			}
			$this->isComplete = $completeStatus;
			return $this;
		}
	}
