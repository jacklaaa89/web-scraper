(function () {

    var index = {

        /**
         * The current request object
         *
         * @type {Object}
         */
        _request: null,

        /**
         * Initialise JS.
         */
        init: function() {
            this._initForm();
        },

        /**
         * Initialises the form.
         *
         * @private
         */
        _initForm: function() {
            var _this = this;
            $('input[type=submit]').off('click').on('click', function(event) {
                 event.preventDefault();

                 var input = $('input[type=url]');
                 var url = input.val();
                 var submit = this;
                 var resultRow = $('.result-row');

                 if (!url.match(
                     /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/
                     )) {
                     return false;
                 }

                 if (_this._request !== null) {
                     _this._request.abort();
                 }

                _this._request = $.ajax({
                    url: '/scrape',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        'url': url
                    },
                    beforeSend: function() {
                        //set state on form.
                        [input, submit].forEach(function (t) {
                            $(t).attr('disabled', 'disabled');
                        });
                        resultRow.hide();
                    },
                    success: function(response) {
                        if (response.success !== undefined && !response.success) {
                            return;
                        }
                        resultRow.find('#url').text(url);
                        resultRow.find('code').html(JSON.stringify(response, null, 5));
                        resultRow.show();
                    },
                    complete: function() {
                        [input, submit].forEach(function (t) {
                            $(t).removeAttr('disabled');
                        });
                    }
                });
            });
        }
    };

    //Initialise JS.
    index.init();
})();