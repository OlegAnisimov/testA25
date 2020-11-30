/**
 * Контрол поля типа "Набор изображений"
 * @param {Object} options настройки поля
 */
var ControlMultiImage = function(options) {

	var wrapper = options.container || null;
	var maxOrder = 0;

	/** Инициализирует поле */
	function init() {
		var files = $('.mimage_wrapper div.multi_image', wrapper);

		if (files.length > 0) {
			$.each(files, function(id, item) {
				var element = $(item);
				var property = {
					file:  element.attr('umi:file'),
					alt:  element.attr('umi:alt'),
					title:  element.attr('umi:title'),
					order:  element.attr('umi:order'),
					id:  element.attr('umi:id'),
					folderHash:  element.attr('umi:folder-hash') || null,
					fileHash:  element.attr('umi:file-hash') || null,
					fieldName:  wrapper.attr('umi:name') || null,
					onGetFileCallback:  element.attr('umi:on_get_file_function') || function() {},
					element:  element
				};

				maxOrder = property.order > maxOrder ? property.order : maxOrder;
				init_control(property);
			});
		}

		initEmptyField(wrapper);

		$('.mimage_wrapper', wrapper).sortable({
			placeholder: 'thumbnail-placeholder',
			helper: 'clone',
			revert: true,
			update: function() {
				updateOrder($('.mimage_wrapper div.multi_image', wrapper));
			}
		});
	}

	/**
	 * Инициализирует контрол поля
	 * @param {Object} property свойства поля
	 */
	function init_control(property) {
		var propertyId = property.id;
		var $container = property.element;
		var selectId = 'miSelect_' + propertyId + '_' + wrapper.attr('id');
		var settings = property;
		var title = property.title;
		var alt = property.alt;
		var file = property.file;

		appendThumbnail({
			file: file,
			thumbnailTitle: file,
			propertyId: propertyId,
			container: $container,
			selectId: selectId,
			alt: alt,
			title: title,
			order: property.order,
			prefix: wrapper.attr('data-prefix'),
			value: $('<img>').attr('src', file),
			label: getLabel('js-image-field-empty'),
			closeButtonHint: getLabel('js-image-field-remove-image'),
			titleButtonHint: title || getLabel('js-image-field-empty-attribute'),
			altButtonHint: alt || getLabel('js-image-field-empty-attribute'),
			isMultiple: true
		});

		var selectedObject = getSelectObject(file, $container);

		if (file !== '') {
			prepareActionButtons({
				id: 'imageAttribute',
				title: true,
				alt: true,
				$container: $container,
				isMultiple: true,
				settings: settings
			});
		}

		window[selectId] = selectedObject[0];

		$container.find('.thumbnail').on('click', function() {
			showElfinderFileBrowser({
				select: selectedObject,
				folder: '.',
				imageOnly: true,
				videoOnly: false,
				folderHash: property.folderHash,
				fileHash: property.fileHash,
				fieldName: property.fieldName,
				lang: options.lang || 'ru',
				isMultiple: 1,
				onGetFileFunction: property.onGetFileCallback
			});
		});

		selectedObject.on('change', function() {
			fileChangeHandler($(this), settings);
		});
	}

	/**
	 * Обработчик изменения файла
	 * @param {jQuery} object текущий объект
	 * @param {Object} property свойство
	 */
	function fileChangeHandler(object, property) {
		property = changeFileInProperty(object, property);

		if (property.id === '') {
			property = fillProperty(property, 'multi_image', maxOrder);
			maxOrder = property.order;
			addEmptyField();
		}

		init_control(property);
		maxOrder = updateOrder($('.mimage_wrapper div.multi_image', wrapper), maxOrder);
	}

	/** Добавляет пустое поле */
	function addEmptyField() {
		wrapper.find('.mimage_wrapper').append($([
			'<div class="emptyfield" ></div>'
		].join('')));

		initEmptyField(wrapper);
	}

	/** Инициализирует пустое поле */
	function initEmptyField(wrapper) {
		init_control({
			file: '',
			alt: '',
			title: '',
			order: -1,
			id: '',
			folderHash:  wrapper.attr('umi:folder-hash') || null,
			fileHash:  wrapper.attr('umi:file-hash') || null,
			fieldName:  wrapper.attr('umi:name') || null,
			onGetFileCallback:  wrapper.attr('umi:on_get_file_function') || function() {},
			element: $('.emptyfield', wrapper),
		});
	}

	init();
};