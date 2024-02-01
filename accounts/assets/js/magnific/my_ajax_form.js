function MyAjaxForm(UserOptions) {
	var __AF = this;
	__AF.Settings = {formData: '', url: '', add: 'add', edit: 'edit', submitRecordId: false, spinnerIcon: 'fa fa-2x fa-spinner fa-spin pull-left text-info', focus: '', _dt10: '', fullScreen: false, formSubmitBtn: 'submitBtn', formLoaded: function ($form, recordId) {
		}, formSaved: function ($form, $record, data) {
		}, formClosed: function () {
		}, extraActionButtons: [], autoShowFeedback: true},
			__AF.loadingImg = '<div class="text-center" id="loadingImg"><img style="height:96px" class="img img-responsive img-thumbnail" src="public/images/gh-spinner.gif"></div>',
			__AF.ajaxFormId = 'ajaxForm' + String(new Date().getTime()) + String(Math.random()).substring(2);
	jQuery.extend(__AF.Settings, UserOptions || {});
	__AF.formLoaded = __AF.Settings.formLoaded;
	__AF.formSaved = __AF.Settings.formSaved;
	__AF.formClosed = __AF.Settings.formClosed;
	delete __AF.Settings.formLoaded;
	delete __AF.Settings.formSaved;
	__AF.iniFormData = __AF.Settings.formData;
	__AF.$FormDialog = null;
	__AF.addFormData = function (k, v) {
		if ('string' == typeof __AF.Settings.formData) {
			__AF.Settings.formData += String(k) + '=' + encodeURIComponent(String(v)) + '&';
		} else {
			__AF.Settings.formData.append(k, v);
		}
	};
	__AF.buildFormData = function () {
		if ('string' == typeof __AF.Settings.formData) {
			__AF.Settings.formData = jQuery('form', __AF.$FormDialog.content).serialize() + '&' + __AF.iniFormData;
			return 'string';
		} else {
			if ('' !== __AF.iniFormData) {
				var ifd = __AF.iniFormData.split('&');
				for (ifdX in ifd) {
					var idfVar = ifd[ifdX].split('=');
					(2 === idfVar.length) ? (__AF.Settings.formData.append(idfVar[0], idfVar[1])) : (__AF.Settings.formData.append(idfVar[0], ''));
				}
			}
			var arr = jQuery('form', __AF.$FormDialog.content).serializeArray();
			for (i in arr) {
				__AF.Settings.formData.append(arr[i].name, arr[i].value);
			}
			jQuery.each(jQuery('input[type=file]', __AF.$FormDialog.content), function (j, fileInput) {
				if ("object" === typeof fileInput.files[0]) {
					__AF.Settings.formData.append(fileInput.name, fileInput.files[0]);
				}
			});
			return 'object';
		}
	};
	__AF.open = function (recordId) {
		var endPoint = __AF.Settings.url + '/' + (recordId < 1 ? (__AF.Settings.add) : (__AF.Settings.edit + '/' + String(recordId)));
		jQuery.magnificPopup.open({
			closeOnBgClick: false, focus: __AF.Settings.focus, alignTop: true, tLoading: '<i class="fa fa-spinner fa-spin fa-spin fa-4x fa-fw text-white"></i>',
			items: {src: getAppURL(endPoint)}, type: 'ajax', ajax: {
				settings: {data: __AF.iniFormData, error: function (x,s,e) {
						if (403 === x.status) {
							notifyUser(_lang.you_do_not_have_enough_previlages_to_access_the_requested_page, 'danger', 'Unable to access page', 'fa fa-exclamation-triangle', 4030);
							console.clear();
						} else {
							defaultAjaxJSONErrorsHandler(x,s,e);
						}
						__AF.$FormDialog.close();
					}
				}
			}, removalDelay: 64, callbacks: {
				ajaxContentAdded: function () { // form is ready
					var $form = $('form', this.content).attr('submit', '');
					__AF.formLoaded($form, recordId);
					// jQuery('input[name=' + __AF.Settings.formSubmitBtn + ']', $form).on('click', function (e) {__AF.saveAjaxForm(e, $form);});
					$form.on('submit', function (e) {
						__AF.saveAjaxForm(e, $form);
					});
					__AF.Settings.formData = (0 < jQuery('input[type=file]', $form).length ? (new FormData()) : __AF.iniFormData);
				}, afterClose: function () {
					__AF.formClosed();
				}
			}
		});
		__AF.$FormDialog = jQuery.magnificPopup.instance;
	};

	__AF.close = function () {
		__AF.$FormDialog.close();
	};
	__AF.saveAjaxForm = function (e, $form) {
		e.preventDefault();
		e.stopPropagation();
		var recordId = (false !== this.Settings.submitRecordId ? this.Settings.submitRecordId : jQuery('input#id', $form).val());
		var ajaxOptions = {url: getAppURL(__AF.Settings.url + '/' + (!(recordId && recordId > 0) ? (__AF.Settings.add) : (__AF.Settings.edit + '/' + String(recordId)))),
			type: 'POST', dataType: 'JSON', error: function (Xhr, Status, Error) {
				_ajax_json_error(Xhr, Status, Error);
			}, beforeSend: function (xhr) {
				jQuery('input[name=' + __AF.Settings.formSubmitBtn + ']', $form).prop('disabled', true).addClass('is-loading').parent().append('<i class="' + __AF.Settings.spinnerIcon + '"></i>');
				__AF.removeFormValidationErrors($form);
			}, success: function (data, textStatus, jqXHR) {
				if (data.success) {
					if (__AF.Settings.autoShowFeedback) {
						notifyUser(_lang.record_saved, 'success', '', 'fa fa-check-circle-o');
					}
					__AF.formSaved($form, data.record, data);
					try {
						(null !== $dataTableObj && 1 === $dataTableObj.length) && ($dataTableObj.fnReloadAjax());
						('' !== __AF.Settings._dt10 && String(__AF.Settings._dt10) in window) && (window[__AF.Settings._dt10].ajax.reload());
					} catch (e) {
					}
					__AF.$FormDialog.close();
				} else {
					jQuery('input[name=' + __AF.Settings.formSubmitBtn + ']', $form).prop('disabled', false).removeClass('is-loading');
					jQuery('i', jQuery('input[name=' + __AF.Settings.formSubmitBtn + ']', $form).parent()).remove();
					__AF.displayFormValidationErrors(data.errors, $form);
				}
			}
		};
		if ('string' !== __AF.buildFormData()) {
			ajaxOptions.cache = false, ajaxOptions.contentType = false, ajaxOptions.processData = false, ajaxOptions.method = 'POST';
		}
		ajaxOptions.data = __AF.Settings.formData;
		__AF.Settings.formData = (0 < jQuery('input[type=file]', $form).length ? (new FormData()) : __AF.iniFormData);
		jQuery.ajax(ajaxOptions);
	};
	__AF.removeFormValidationErrors = function ($form) {
		jQuery('p.validation-errors-msg', $form).remove();
	};
	__AF.displayFormValidationErrors = function (errors, $form) {
		var m = '';
		for (e in errors) {
			var $container = jQuery('#' + e, $form);
			if (1 === $container.length) {
				$container.parent().append('<p class="alert alert-danger validation-errors-msg">' + errors[e] + '</p>');
			} else {
				m += errors[e] + '<br />';
			}
		}
		('' !== m) && (notifyUser(m, 'danger', _lang.an_error_occured, 'fa fa-exclamation', 10000));
	};
}