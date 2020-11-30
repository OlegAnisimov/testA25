<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM	"ulang://i18n/constants.dtd:file">

<xsl:stylesheet	version="1.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:date="http://exslt.org/dates-and-times"
				xmlns:udt="http://umi-cms.ru/2007/UData/templates"
				xmlns:umi="http://www.umi-cms.ru/TR/umi"
				xmlns:xlink="http://www.w3.org/1999/xlink"
				exclude-result-prefixes="xsl date udt xlink">



	<xsl:template match="/result[@module = 'appointment' and @method = 'page']">
		<xsl:variable name="appointment-page-id" select="$document-page-id" />
		<xsl:variable name="appointment-settings" select="document(concat('upage://', $appointment-page-id))//group[@name = 'appointment']" />


		<script src='/templates/demodizzy/js/appointment/lib/moment.js' type="text/javascript"></script>
		<script src='/templates/demodizzy/js/appointment/lib/jQuery.hoverForMore.js' type="text/javascript"></script>
		<script src='/templates/demodizzy/js/appointment/lib/jQuery.inputmask.js' type="text/javascript"></script>
		<script src='/templates/demodizzy/js/appointment/lib/ru.js' type="text/javascript"></script>
		<script src='/templates/demodizzy/js/appointment/lib/underscore.js' type="text/javascript"></script>
		<script src='/templates/demodizzy/js/appointment/lib.js' type="text/javascript"></script>

		<div class="appointments main-container">
			<script src='/templates/demodizzy/js/appointment/main.js' type="text/javascript"></script>
			<div id="wrapper">

				<!-- Начало блока Онлайн запись -->
				<div class="online-entry__wrapper">

					<!-- Начало первого шага -->
					<div class="online-entry__step selected" id="data-service">
						<div class="online-entry__title">
							<span umi:element-id="{$appointment-page-id}" umi:field-name="appoint_service_choice_title" umi:field-type="text" umi:empty="&appoint_service_choice_title;">
								<xsl:choose>
									<xsl:when test="$appointment-settings/property[@name = 'appoint_service_choice_title']">
										<xsl:value-of select="$appointment-settings/property[@name = 'appoint_service_choice_title']/value" disable-output-escaping="yes" />
									</xsl:when>
									<xsl:otherwise>
										Выберите услугу:
									</xsl:otherwise>
								</xsl:choose>
							</span>
							&nbsp;<span class="online-entry__choise"></span>
						</div>
						<div class="online-entry__content">
							<div class="column-60pct">
								<div class="service-choose">
									<img id="service-loader" src="/templates/demodizzy/images/appointments/loader.gif" />
								</div>
							</div>
							<div class="column-40pct">
								<div class="recording-time" style="display: none;">
									<div class="recording-time__title">Доступное время записи:<div class="recording-time__note">Свободно</div></div>
									<div class="recording-time-selection__wrapper">

									</div>
									<div class="free-days">В другие дни - свободно*</div>
									<div class="recording-time__title">Мастера, выполняющие услугу:</div>
									<ul class="masters-list">
									</ul>
								</div>
								<div class="hint-step" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_hint_step_text" umi:field-type="wysiwyg" umi:empty="&empty-appoint-hint-step;">
									<xsl:choose>
										<xsl:when test="$appointment-settings/property[@name = 'appoint_hint_step_text']">
											<xsl:value-of select="$appointment-settings/property[@name = 'appoint_hint_step_text']/value" disable-output-escaping="yes" />
										</xsl:when>
										<xsl:otherwise>
											Выберите одну базовую/главную услугу. Дополнительные услуги уточнит оператор. Указаны базовые цены. Прайс не является публичной офертой.
										</xsl:otherwise>
									</xsl:choose>
								</div>
							</div>
						</div>
					</div>
					<!-- Конец первого шага -->

					<!-- Начало второго шага -->
					<div class="online-entry__step" id="data-personal">
						<div class="online-entry__title">
							<span class="online-entry__nonactive" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_personal_step_title" umi:field-type="text" umi:empty="&appoint_personal_step_title;">
								<xsl:choose>
									<xsl:when test="$appointment-settings/property[@name = 'appoint_personal_step_title']">
										<xsl:value-of select="$appointment-settings/property[@name = 'appoint_personal_step_title']/value" disable-output-escaping="yes" />
									</xsl:when>
									<xsl:otherwise>
										Специалист (пожалуйста, укажите)
									</xsl:otherwise>
								</xsl:choose>
							</span>
							<span class="online-entry__current" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_personal_choice_title" umi:field-type="text" umi:empty="&appoint_personal_choice_title;">
								<xsl:choose>
									<xsl:when test="$appointment-settings/property[@name = 'appoint_personal_choice_title']">
										<xsl:value-of select="$appointment-settings/property[@name = 'appoint_personal_choice_title']/value" disable-output-escaping="yes" />
									</xsl:when>
									<xsl:otherwise>
										Выберите специалиста:
									</xsl:otherwise>
								</xsl:choose>
							</span>
							&nbsp;<span class="online-entry__choise"></span>
						</div>
						<div class="online-entry__content">
							<div class="selection-specialist">
								<div class="selection-specialis_hint">
									<span class="online-entry__btn" id="operator-click" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_dont_care_button" umi:field-type="string" umi:empty="&empty-appoint-dont-care-button;">
										<xsl:choose>
											<xsl:when test="$appointment-settings/property[@name = 'appoint_dont_care_button']/value">
												<xsl:value-of select="$appointment-settings/property[@name = 'appoint_dont_care_button']/value" />
											</xsl:when>
											<xsl:otherwise>
												Мне все равно
											</xsl:otherwise>
										</xsl:choose>
									</span>
									<span class="operator-prompt" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_dont_care_hint" umi:field-type="string" umi:empty="&empty-appoint-dont-care-hint;">
										<xsl:choose>
											<xsl:when test="$appointment-settings/property[@name = 'appoint_dont_care_hint']/value">
												<xsl:value-of select="$appointment-settings/property[@name = 'appoint_dont_care_hint']/value" />
											</xsl:when>
											<xsl:otherwise>
												Если вы не хотите выбирать (оператор подскажет)
											</xsl:otherwise>
										</xsl:choose>
									</span>
								</div>
								<div class="master-list">
								</div>
							</div>
						</div>
					</div>
					<!-- Конец второго шага -->

					<!-- Начало третьего шага -->
					<div class="online-entry__step" id="data-entry">
						<div class="online-entry__title">
							<span class="online-entry__nonactive" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_date_step_title" umi:field-type="text" umi:empty="&appoint_date_step_title;">
								<xsl:choose>
									<xsl:when test="$appointment-settings/property[@name = 'appoint_date_step_title']">
										<xsl:value-of select="$appointment-settings/property[@name = 'appoint_date_step_title']/value" disable-output-escaping="yes" />
									</xsl:when>
									<xsl:otherwise>
										Дата визита (пожалуйста, укажите)
									</xsl:otherwise>
								</xsl:choose>
							</span>
							<span class="online-entry__current" umi:element-id="{$appointment-page-id}" umi:field-name="appoint_date_choice_title" umi:field-type="text" umi:empty="&appoint_date_choice_title;">
								<xsl:choose>
									<xsl:when test="$appointment-settings/property[@name = 'appoint_date_choice_title']">
										<xsl:value-of select="$appointment-settings/property[@name = 'appoint_date_choice_title']/value" disable-output-escaping="yes" />
									</xsl:when>
									<xsl:otherwise>
										Дата визита:
									</xsl:otherwise>
								</xsl:choose>
							</span>
							&nbsp;<span class="online-entry__choise"></span>
						</div>
						<div class="online-entry__content">
							<div class="column-70pct">
								<div class="date-visit__wrapper">
									<div id="datepicker"></div>
								</div>
							</div>
							<div class="column-30pct">
								<div class="choose-time" style="display: none;">
									<div class="choose-time__title">Выберите время визита</div>
									<div class="choose-time_selection">

									</div>
									<div class="description-step__status">
										<div class="status-busy">Занятно</div>
										<div class="status-free">Свободно</div>
										<div class="status-output">Выходной</div>
									</div>
								</div>
								<div class="description-step">
									<div class="description-step__status">
										<div class="status-busy">Занятно</div>
										<div class="status-free">Свободно</div>
										<div class="status-output">Выходной</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Конец третьего шага -->

					<!-- Начало четвертого шага -->
					<div class="online-entry__step" id="data-confirm">
						<div class="online-entry__title">
							<span umi:element-id="{$appointment-page-id}" umi:field-name="appoint_confirm_step_title" umi:field-type="text" umi:empty="&appoint_confirm_step_title;">
								<xsl:choose>
									<xsl:when test="$appointment-settings/property[@name = 'appoint_confirm_step_title']">
										<xsl:value-of select="$appointment-settings/property[@name = 'appoint_confirm_step_title']/value" disable-output-escaping="yes" />
									</xsl:when>
									<xsl:otherwise>
										Подтверждение заявки на визит (пожалуйста, заполните)
									</xsl:otherwise>
								</xsl:choose>
							</span>
						</div>
						<div class="online-entry__content">
							<div class="final-registration">
								<div class="column-50pct">
									<div class="online-entry__ordering">
										<form action="/appointment/postAppointment/" method="POST">
											<input type="text" name="name" placeholder="Ваше имя" data-valid="text" />
											<input type="text" name="phone" placeholder="Телефон" data-valid="phone" />
											<input type="text" name="email" placeholder="E-mail" data-valid="email" data-inputmask="'alias': 'email'" />

											<textarea name="commentary" placeholder="Комментарий"></textarea>

											<xsl:if test="$is-admin = 1">
												<div class="hidden">Текст кнопки:
													<span
															umi:element-id="{$appointment-page-id}"
															umi:field-name="appoint_book_time_button"
															umi:field-type="string"
															umi:empty="&empty-appoint-book-time-button;">
														<xsl:value-of select="$appointment-settings/property[@name = 'appoint_book_time_button']/value" />
													</span>
												</div>
											</xsl:if>
											<input type="submit" id="create-appointment">
												<xsl:attribute name="value">
													<xsl:choose>
														<xsl:when test="$appointment-settings/property[@name = 'appoint_book_time_button']/value">
															<xsl:value-of select="$appointment-settings/property[@name = 'appoint_book_time_button']/value" />
														</xsl:when>
														<xsl:otherwise>&empty-appoint-book-time-button;</xsl:otherwise>
													</xsl:choose>
												</xsl:attribute>

											</input>
										</form>
									</div>
								</div>
								<div class="column-50pct">
									<div class="online-entry__ordering">
										<div class="final-registration__title">Пожалуйста, проверьте вашу заявку</div>
										<ul></ul>
										<div class="final-registration__hint">Если нашли ошибку - нажмите "Изменить" выше.</div>
										<div class="final-registration__hint final-registration__error"></div>
									</div>
								</div>
							</div>
							<div class="communication-admin">
								<div class="communication-admin__content">
									<span
											umi:element-id="{$appointment-page-id}"
											umi:field-name="appoint_book_time_hint"
											umi:field-type="string"
											umi:empty="&empty-appoint-book-time-hint;">После заполнения формы администратор свяжется с вами по телефону.</span>
									<br/>

									<a href="/contacts">Контакты администратора</a>
								</div>
							</div>
						</div>
					</div>
					<!-- Конец четвертого шага -->

				</div>

				<!-- Конец блока Онлайн запись -->
			</div>
			<script src='/templates/demodizzy/js/appointment/datepicker.js' type="text/javascript"></script>
		</div>
	</xsl:template>
</xsl:stylesheet>