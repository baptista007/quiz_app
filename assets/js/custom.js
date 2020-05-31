(function ($) {
    "use strict"; // Start of use strict

    // Toggle the side navigation
    $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
        $("body").toggleClass("sidebar-toggled");
        $(".sidebar").toggleClass("toggled");

        if ($(".sidebar").hasClass("toggled")) {
            $('.sidebar .collapse').collapse('hide');
            $('.sm-screen-logo').show();
            $('.lg-screen-logo').hide();
        } else {
            $('.sm-screen-logo').hide();
            $('.lg-screen-logo').show();
        }
    });

    // Close any open menu accordions when window is resized below 768px
    $(window).resize(function () {
        if ($(window).width() < 768) {
            $('.sidebar .collapse').collapse('hide');
        }
        ;

        // Toggle the side navigation when window is resized below 480px
        if ($(window).width() < 480 && !$(".sidebar").hasClass("toggled")) {
            $("body").addClass("sidebar-toggled");
            $(".sidebar").addClass("toggled");
            $('.sidebar .collapse').collapse('hide');
        }
        ;
    });

    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
        if ($(window).width() > 768) {
            var e0 = e.originalEvent,
                    delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });

    // Scroll to top button appear
    $(document).on('scroll', function () {
        var scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });

    // Smooth scrolling using jQuery easing
    $(document).on('click', 'a.scroll-to-top', function (e) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($($anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });

})(jQuery); // End of use strict


/**
 * START (Saudai)
 * collapsing the target cards in golas
 */
var $cell = $(".wrapper .card");

//open and close card when clicked on card
$cell.find(".js-expander").click(function () {
    var $thisCell = $(this).closest(".wrapper .card");

    if ($thisCell.hasClass("is-collapsed")) {
        $cell
                .not($thisCell)
                .removeClass("is-expanded")
                .addClass("is-collapsed")
                .addClass("is-inactive");
        $thisCell.removeClass("is-collapsed").addClass("is-expanded");

        if ($cell.not($thisCell).hasClass("is-inactive")) {
            //do nothing
        } else {
            $cell.not($thisCell).addClass("is-inactive");
        }
    } else {
        $thisCell.removeClass("is-expanded").addClass("is-collapsed");
        $cell.not($thisCell).removeClass("is-inactive");
    }
});

//close card when click on cross
$cell.find(".js-collapser").click(function () {
    var $thisCell = $(this).closest(".wrapper .card");

    $thisCell.removeClass("is-expanded").addClass("is-collapsed");
    $cell.not($thisCell).removeClass("is-inactive");
});
/**
 * END: colapsing target cards
 */

function openModalRemoteContent(url, title, close_btn_url, close_btn_modal_title) {
    var $modal = $('#ajax-modal');

    doGet(url, function (data) {
        if (data.indexOf('class="modal-content"') === -1) {
            var prep = '<div class="modal-dialog modal-lg" role="document">';
            prep += '<div class="modal-content">';

            if (title) {
                prep += '<div class="modal-header">';
                prep += '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                prep += '<h4 class="modal-title">';
                prep += title;
                prep += '</h4>';
                prep += '</div>';
            }

            prep += '<div class="modal-body">';
            prep += data;
            prep += '</div>';

            if (close_btn_url) {
                prep += '<div class="modal-footer">';
                prep += '<button type="button" class="btn btn-default" onclick="openModalRemoteContent(\'' + close_btn_url + '\', \'' + close_btn_modal_title + '\')">Close</button>';
                prep += '</div>';
            }

            prep += '</div>';
            prep += '</div>';

            data = prep;
        }

        $modal.html(data).modal();
    });
}

function isEmpty(value) {
    //Undefined or null
    if (typeof (value) === 'undefined'
            || value === null) {
        return true;
    }

    //Arrays && Strings
    if (typeof (value.length) !== 'undefined') {
        return value.length === 0;
    }

    //Numbers or boolean
    if (typeof (value) === 'number'
            || typeof (value) === 'boolean') {
        return false;
    }

    //Objects
    var count = 0;

    for (var i in value) {
        if (value.hasOwnProperty(i)) {
            count++;
        }
    }

    return count === 0;
}

function focusField(field, tab) {
    $('#' + field).focus();
}

function processFormErrors($form, errors)
{
    $.each(errors, function (index, error)
    {
        var $input = document.querySelector('input[name="' + index + '"]');

        if ($input) {
            $input.setCustomValidity(error);

            $input.onkeyup = function () {
                this.setCustomValidity('');
            };

            $input.focus();
        }

//        var $input = $(':input[name=' + index + ']', $form);
//        
//        if ($input.prop('type') === 'file') {
//            $('#input-' + $input.prop('name')).append('<div class="help-block error">' + error + '</div>')
//                    .parent()
//                    .addClass('has-error');
//        } else {
//            var append = (!$input.parent().hasClass('input-group') ? $input : $input.parent());
//            append.after('<small  class="text-danger">' + error + '</small >')
//                    .parent()
//                    .addClass('has-danger');
//        }
    });
}

function toggleSubmitDisabled($submitButton) {

    if ($submitButton.hasClass('disabled')) {
        $submitButton.attr('disabled', false)
                .removeClass('disabled')
                .val($submitButton.data('original-text'));
        return;
    }

    $submitButton.data('original-text', $submitButton.val())
            .attr('disabled', true)
            .addClass('disabled')
            .val('Working...');
}

/**
 * Replaces a parameter in a URL with a new parameter
 *
 * @param url
 * @param paramName
 * @param paramValue
 * @returns {*}
 */
function replaceUrlParam(url, paramName, paramValue) {
    var pattern = new RegExp('\\b(' + paramName + '=).*?(&|$)')
    if (url.search(pattern) >= 0) {
        return url.replace(pattern, '$1' + paramValue + '$2');
    }
    return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
}



/**
 * Shows users a message.
 * Currently uses humane.js
 *
 * @param string message
 * @returns void
 */
function showMessage(message) {
    $("html, body").animate({ scrollTop: 0 }, "slow");
    $('.flash-msg-container').html(message);

    setTimeout(function () {
        $('.flash-msg-container').html('');
    }, 4500);
}

function showHelp(message) {
//    humane.log(message, {
//        timeout: 12000
//    });
}

function hideMessage() {
//    humane.remove();
}

function hideFlashMsg() {
    var elem = $('#flashmsgholder');
    if (elem.length > 0) {
        var duration = elem.attr("data-show-duration");
        if (duration > 0) {
            window.setTimeout(function () {
                elem.fadeOut();
            }, duration)
        }
    }
}

function autocompleteClearValue(input) {
    if (input && typeof input === 'object') {
        input.value = '';

        if (input.hasAttribute("data-autocomplete-value-control")) {
            var elem = document.getElementById(input.getAttribute("data-autocomplete-value-control"));

            if (elem) {
                elem.value = "";
            }
        }
    }
}

function closeModal(id) {
    $('#' + id).modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function previewFile(file) {
    $('#preview-modal-holder').load(siteAddr + 'shared/get_file_preview_local?file=' + file);
    $('#preview-modal').modal('show');
}

function previewFileGeneral(file) {
    $('#gen-preview-modal-holder').load(siteAddr + 'shared/get_file_preview_local?file=' + file);
    $('#gen-preview-modal').modal('show');
}

$(function () {
    AutoComplete();

    $('#main-page-modal').on('hidden.bs.modal', function () {
        $('#main-page-modal-body').html('<img src="' + siteAddr + '/assets/images/spinner.gif" alt="Spinner" />');
    });


    /*
     * --------------------
     * Ajaxify those forms
     * --------------------
     *
     * All forms with the 'ajax' class will automatically handle showing errors etc.
     *
     */
    $('form.ajax').ajaxForm({
        delegation: true,
        beforeSubmit: function (formData, jqForm, options) {
            $(jqForm[0])
                    .find('.error.help-block')
                    .remove();
            $(jqForm[0]).find('.has-error')
                    .removeClass('has-error');

            var $submitButton = $(jqForm[0]).find('input[type=submit]');
            toggleSubmitDisabled($submitButton);
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $('.uploadProgress').show().html('Uploading Images - ' + percentComplete + '% Complete...    ');
        },
        error: function (data, statusText, xhr, $form) {
            // Form validation error.
            if (422 == data.status) {
                processFormErrors($form, $.parseJSON(data.responseText));
                return;
            }

            var msg = '<div class="alert alert-dnager">Whoops!, it looks like something went wrong on our servers.\n\
              Please try again, or contact support if the problem persists.</div>';

            if (!isEmpty($form.attr('data-feedback-div')) && $('#' + $form.attr('data-feedback-div')).length) {
                $('#' + $form.attr('data-feedback-div')).html('<div class="alert alert-danger">' + msg + '</div>');
            } else {
                showMessage('<div class="alert alert-danger">' + msg + '</div>');
            }

            var $submitButton = $form.find('input[type=submit]');
            toggleSubmitDisabled($submitButton);

            $('.uploadProgress').hide();
        },
        success: function (data, statusText, xhr, $form) {
            if (data.success) {
                if ($form.hasClass('reset')) {
                    $form.resetForm();
                }

                if ($form.hasClass('closeModalAfter')) {
                    $('.modal, .modal-backdrop').fadeOut().remove();
                }

                var $submitButton = $form.find('input[type=submit]');
//                toggleSubmitDisabled($submitButton);

                if (typeof data.message !== 'undefined') {
                    if (!isEmpty($form.attr('data-feedback-div')) && $('#' + $form.attr('data-feedback-div')).length) {
                        $('#' + $form.attr('data-feedback-div')).html('<div class="alert alert-success">' + data.message + '</div>');
                    } else {
                        showMessage('<div class="alert alert-success">' + data.message + '</div>');
                    }
                }

                if (typeof data.runThis !== 'undefined') {
                    eval(data.runThis);
                }

                if (typeof data.redirectUrl !== 'undefined') {
                    setTimeout(function () {
                        window.location.href = data.redirectUrl;
                    }, 2000);
                }
            } else {
                if (!isEmpty($form.attr('data-feedback-div')) && $('#' + $form.attr('data-feedback-div')).length) {
                    $('#' + $form.attr('data-feedback-div')).html('<div class="alert alert-danger">' + data.message + '</div>');
                } else {
                    showMessage('<div class="alert alert-danger">' + data.message + '</div>');
                }

                var $submitButton = $form.find('input[type=submit]');
                toggleSubmitDisabled($submitButton);

                if (!isEmpty(data.fieldmessages)) {
                    processFormErrors($form, data.fieldmessages);
                }
            }

            $('.uploadProgress').hide();
            return false;
        },
        dataType: 'json'
    });
});

// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
// https://gist.github.com/mattheyan/46a230da86a0894a5ff654b9139ec5f2
function escapeRegExp(str) {
    return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function convertWildcardStringToRegExp(expression) {
    var terms = expression.split('*');

    var trailingWildcard = false;

    var expr = '';
    for (var i = 0; i < terms.length; i++) {
        if (terms[i]) {
            if (i > 0 && terms[i - 1]) {
                expr += '.*';
            }
            trailingWildcard = false;
            expr += escapeRegExp(terms[i]);
        } else {
            trailingWildcard = true;
            expr += '.*';
        }
    }

    if (!trailingWildcard) {
        expr += '.*';
    }

    return new RegExp('^' + expr + '$', 'i');
}

function filterArrayByWildcard(array, expression) {
	var regex = convertWildcardStringToRegExp(expression);
	//console.log('RegExp: ' + regex);
	return array.filter(function(item) {
		return regex.test(item);
	});
}