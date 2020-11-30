/**
 * Контрол поля типа "Изображения"
 * @param {Object} options настройки поля
 */
var ControlImageFile = function(options) {
	var wrapper = options.container || null;

	/** Инициализирует поле */
	function init() {
		var wrapperId = wrapper.attr('id');
		var	selectId = 'imageControlSelect_' + wrapperId;
		var id = 'imageField_' + wrapperId;

		initControl({
			file: wrapper.attr('umi:file'),
			alt: wrapper.attr('umi:alt'),
			title: wrapper.attr('umi:title'),
			selectId: selectId,
			element: $('#' + id, wrapper)
		});
	}

	/**
	 * Инициализирует контрол поля
	 * @param {Object} property свойства поля
	 */
	function initControl(property) {
		var file = property.file;
		var alt = property.alt;
		var title = property.title;
		var selectId = property.selectId;
		var container = property.element;
		var settings = property;

		appendThumbnail({
			file: file,
			thumbnailTitle: file,
			propertyId: wrapper.attr('umi:field-id'),
			container: container,
			selectId: selectId,
			alt: alt,
			title: title,
			prefix: 'data[images]',
			value: $('<img>').attr('src', file),
			label: getLabel('js-image-field-empty'),
			closeButtonHint: getLabel('js-image-field-remove-image'),
			titleButtonHint: title || getLabel('js-image-field-empty-attribute'),
			altButtonHint: alt || getLabel('js-image-field-empty-attribute'),
			emptyInputName: wrapper.attr('umi:input-name'),
			isMultiple: false
		});

		var selectedObject = $('#' + selectId, wrapper);

		container.find('.thumbnail').on('click', function() {
			if (wrapper.attr('umi:filemanager') === 'elfinder') {
				showElfinderFileBrowser({
					select: selectedObject,
					folder: '.',
					imageOnly: true,
					videoOnly: false,
					folderHash: wrapper.attr('umi:folder-hash'),
					fileHash: wrapper.attr('umi:file-hash'),
					lang: options.lang || 'ru',
					isMultiple: false,
					onGetFileFunction: wrapper.attr('umi:on_get_file_function'),
					fieldName:  wrapper.attr('umi:name') || null,
				});
			}
		});

		if (file !== '') {
			prepareActionButtons({
				id: 'imageAttribute',
				title: true,
				alt: true,
				$container: container,
				isMultiple: false,
				settings: {
					selectedObject: selectedObject,
					wrapper: wrapper
				}
			});
		}

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
		initControl(changeFileInProperty(object, property));
	}

	init();
};
