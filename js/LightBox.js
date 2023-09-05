var SmartBoxDoamin;
(function () {
    window.SmartBox = window.SmartBox || {};
    SmartBox.Checkout = (function () {
        var configure = {};
        function showSmartBox() {
            if (this.configure) {
                // debugger;
                var url = GetSmartBoxUrl(this.configure, true, true);
                var ifrm = document.createElement("iframe");
                ifrm.id = "SmartBox";
                ifrm.setAttribute("src", url);
                ifrm.style.zIndex = "999999";
                ifrm.style.display = "block";
                ifrm.style.backgroundColor = "transparent";
                ifrm.style.border = "0px none transparent";
                ifrm.style.overflowX = "hidden"; //hidden
                ifrm.style.overflowY = "auto"; //scroll//auto

                ifrm.style.visibility = "visible";
                ifrm.style.margin = "0px";
                ifrm.style.padding = "0px";
                ifrm.style.position = "fixed";
                ifrm.style.left = "50";
                ifrm.style.top = "50"
                // ifrm.style.bottom = "0px";
                // ifrm.style.right = "0px";
                ifrm.style.width = "100%";
                ifrm.style.height = "100%";

                document.body.appendChild(ifrm);




            } else {
                console.log('no configuration');
            }
        };

        function closeSmartBox() {

            var elem = document.getElementById('SmartBox');//framePaymentPage,,,,,,,,,SmartBox
            //var elem2 = document.getElementById('framePaymentPage');//framePaymentPage,,,,,,,,,SmartBox
            if (elem != null && elem.parentNode != null) {
                elem.parentNode.removeChild(elem);
            }
            // $('#frameDiv').hide();

        };

        function showPaymentPage() {
            if (this.configure) {
                window.location = GetSmartBoxUrl(this.configure, true, true);
            } else {
                console.log('no configuration');
            }
        };

        function GetSmartBoxUrl(configure, hideCloseButton, isEnableReturnUrl) {
            if (configure) {
                SmartBoxDoamin = 'https://checkout.amwalpg.com';


                var paymentMethodFromSmartBox = null;
                var mId = 0;
                var tId = 0;
                var orderId = "";
                var amount = 0;
                var MerchantReference = "";
                var configPath = window.SmartBox.Checkout.configure;
                var secureHash = "";
                var currencyId = "";
                var languageId = "";
                var trxDateTime = "";
                var paymentViewType = "";
                if (typeof (configPath.paymentMethodFromSmartBox) !== 'undefined') {
                    paymentMethodFromSmartBox = configPath.paymentMethodFromSmartBox;
                }

                if (typeof (configPath.OrderId) !== 'undefined' && configPath.OrderId !== null) {
                    orderId = configPath.OrderId;

                }
                if (typeof (configPath.MID) !== 'undefined' && configPath.MID !== null && !isNaN(configPath.MID)) {
                    mId = configPath.MID;
                }
                if (typeof (configPath.TID) !== 'undefined' && configPath.TID !== null && !isNaN(configPath.TID)) {
                    tId = configPath.TID;
                }

                if (typeof (configPath.AmountTrxn) !== 'undefined' && configPath.AmountTrxn !== null && !isNaN(configPath.AmountTrxn)) {
                    amount = configPath.AmountTrxn;
                }
                if (typeof (configPath.CurrencyId) !== 'undefined' && configPath.CurrencyId !== null && !isNaN(configPath.CurrencyId)) {
                    currencyId = configPath.CurrencyId;
                }
                if (typeof (configPath.LanguageId) !== 'undefined' && configPath.LanguageId !== null && !isNaN(configPath.LanguageId)) {
                    languageId = configPath.LanguageId;
                }
                if (typeof (configPath.MerchantReference) !== 'undefined') {
                    MerchantReference = configPath.MerchantReference;
                }

                if (typeof (configPath.ReturnUrl) !== 'undefined' && configPath.ReturnUrl !== null) {
                    returnUrl = configPath.ReturnUrl;
                }
                if (typeof (configPath.SecureHash) !== 'undefined') {
                    secureHash = configPath.SecureHash;
                }

                if (typeof (configPath.TrxDateTime) !== 'undefined') {
                    trxDateTime = configPath.TrxDateTime;
                }

                if (typeof (configPath.PaymentViewType) !== 'undefined') {
                    paymentViewType = configPath.PaymentViewType;
                }

                if (typeof (configPath.AdditionalCustomerData) !== 'undefined' && configPath.AdditionalCustomerData !== null) {
                    if (typeof (configPath.AdditionalCustomerData.CustomerEmail) !== 'undefined' && configPath.AdditionalCustomerData.CustomerEmail !== null) {
                        customerEmail = configPath.AdditionalCustomerData.CustomerEmail;
                    }
                    if (typeof (configPath.AdditionalCustomerData.CustomerMobile) !== 'undefined' && configPath.AdditionalCustomerData.CustomerMobile !== null) {
                        customerMobile = configPath.AdditionalCustomerData.CustomerMobile;
                    }
                    if (typeof (configPath.AdditionalCustomerData.MessageCustomer) !== 'undefined' && configPath.AdditionalCustomerData.MessageCustomer !== null) {
                        messageCustomer = configPath.AdditionalCustomerData.MessageCustomer;
                    }
                    if (typeof (configPath.AdditionalCustomerData.MerchantCustomerId) !== 'undefined' && configPath.AdditionalCustomerData.MerchantCustomerId !== null) {
                        merchantCustomerId = configPath.AdditionalCustomerData.MerchantCustomerId;
                    }
                    if (typeof (configPath.AdditionalCustomerData.CurrencyTwo) !== 'undefined' && configPath.AdditionalCustomerData.CurrencyTwo !== null) {
                        currencyTwo = configPath.AdditionalCustomerData.CurrencyTwo;
                    }

                    if (typeof (configPath.AdditionalCustomerData.CurrencyTwoValue) !== 'undefined' && configPath.AdditionalCustomerData.CurrencyTwoValue !== null) {
                        currencyTwoValue = configPath.AdditionalCustomerData.CurrencyTwoValue;
                    }

                    if (typeof (configPath.AdditionalCustomerData.AdditionalFees) !== 'undefined' && configPath.AdditionalCustomerData.AdditionalFees !== null && !isNaN(configPath.AdditionalCustomerData.AdditionalFees)) {
                        additionalFees = configPath.AdditionalCustomerData.AdditionalFees;
                    }


                    if (typeof (configPath.AdditionalCustomerData.ShowCustomerEmail) !== 'undefined' && configPath.AdditionalCustomerData.ShowCustomerEmail !== null) {
                        showCustomerEmail = configPath.AdditionalCustomerData.ShowCustomerEmail;
                    }


                    if (typeof (configPath.AdditionalCustomerData.ShowCustomerMobile) !== 'undefined' && configPath.AdditionalCustomerData.ShowCustomerMobile !== null) {
                        showCustomerMobile = configPath.AdditionalCustomerData.ShowCustomerMobile;
                    }


                    if (typeof (configPath.AdditionalCustomerData.ShowMessageCustomer) !== 'undefined' && configPath.AdditionalCustomerData.ShowMessageCustomer !== null) {
                        showMessageCustomer = configPath.AdditionalCustomerData.ShowMessageCustomer;
                    }


                    if (typeof (configPath.AdditionalCustomerData.ShowMerchantCustomerId) !== 'undefined' && configPath.AdditionalCustomerData.ShowMerchantCustomerId !== null) {
                        showMerchantCustomerId = configPath.AdditionalCustomerData.ShowMerchantCustomerId;
                    }

                    if (typeof (configPath.AdditionalCustomerData.ShowCurrencyTwo) !== 'undefined' && configPath.AdditionalCustomerData.ShowCurrencyTwo !== null) {
                        showCurrencyTwo = configPath.AdditionalCustomerData.ShowCurrencyTwo;
                    }

                    if (typeof (configPath.AdditionalCustomerData.ShowAdditionalFees) !== 'undefined' && configPath.AdditionalCustomerData.ShowAdditionalFees !== null) {
                        showAdditionalFees = configPath.AdditionalCustomerData.ShowAdditionalFees;
                    }

                    if (typeof (configPath.AdditionalCustomerData.ConsumerEnterAmount) !== 'undefined' && configPath.AdditionalCustomerData.ConsumerEnterAmount !== null) {
                        consumerEnterAmount = configPath.AdditionalCustomerData.ConsumerEnterAmount;
                    }

                }

                if (typeof (configPath.SmartBoxColorConfig) !== 'undefined' && configPath.SmartBoxColorConfig !== null) {

                    if (typeof (configPath.SmartBoxColorConfig.TopBarColor) !== 'undefined' && configPath.SmartBoxColorConfig.TopBarColor !== null) {
                        topBarColor = configPath.SmartBoxColorConfig.TopBarColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.ButtonPayColor) !== 'undefined' && configPath.SmartBoxColorConfig.ButtonPayColor !== null) {
                        buttonPayColor = configPath.SmartBoxColorConfig.ButtonPayColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.BodyBackgroundColor) !== 'undefined' && configPath.SmartBoxColorConfig.BodyBackgroundColor !== null) {
                        bodyBackgroundColor = configPath.SmartBoxColorConfig.BodyBackgroundColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.Textcolor) !== 'undefined' && configPath.SmartBoxColorConfig.Textcolor !== null) {
                        textcolor = configPath.SmartBoxColorConfig.Textcolor;
                    }


                    if (typeof (configPath.SmartBoxColorConfig.LinkTextColor) !== 'undefined' && configPath.SmartBoxColorConfig.LinkTextColor !== null) {
                        linkTextColor = configPath.SmartBoxColorConfig.LinkTextColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.ScrollbarColor) !== 'undefined' && configPath.SmartBoxColorConfig.ScrollbarColor !== null) {
                        scrollbarColor = configPath.SmartBoxColorConfig.ScrollbarColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.ScrollbarBackgroundColor) !== 'undefined' && configPath.SmartBoxColorConfig.ScrollbarBackgroundColor !== null) {
                        scrollbarBackgroundColor = configPath.SmartBoxColorConfig.ScrollbarBackgroundColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.PaymentInfoBackgroundColor) !== 'undefined' && configPath.SmartBoxColorConfig.PaymentInfoBackgroundColor !== null) {
                        paymentInfoBackgroundColor = configPath.SmartBoxColorConfig.PaymentInfoBackgroundColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.PaymentInfoLineColor) !== 'undefined' && configPath.SmartBoxColorConfig.PaymentInfoLineColor !== null) {
                        paymentInfoLineColor = configPath.SmartBoxColorConfig.PaymentInfoLineColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.HeaderOrFooterLineColor) !== 'undefined' && configPath.SmartBoxColorConfig.HeaderOrFooterLineColor !== null) {
                        headerOrFooterLineColor = configPath.SmartBoxColorConfig.HeaderOrFooterLineColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.CloseButtonColor) !== 'undefined' && configPath.SmartBoxColorConfig.CloseButtonColor !== null) {
                        closeButtonColor = configPath.SmartBoxColorConfig.CloseButtonColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.CloseButtonBackgroundColor) !== 'undefined' && configPath.SmartBoxColorConfig.CloseButtonBackgroundColor !== null) {
                        closeButtonBackgroundColor = configPath.SmartBoxColorConfig.CloseButtonBackgroundColor;
                    }

                    if (typeof (configPath.SmartBoxColorConfig.LogoOne) !== 'undefined' && configPath.SmartBoxColorConfig.LogoOne !== null) {
                        logoOne = configPath.SmartBoxColorConfig.LogoOne;
                    }
                }
                // debugger;
                var SmartBoxHostedQueryString = '/?';
                SmartBoxHostedQueryString += 'MID=' + mId + '&';
                SmartBoxHostedQueryString += 'MerchantReference=' + MerchantReference + '&';
                SmartBoxHostedQueryString += 'Amount=' + amount + '&';
                SmartBoxHostedQueryString += 'TID=' + tId + '&';
                SmartBoxHostedQueryString += 'SecureHash=' + secureHash + '&';
                SmartBoxHostedQueryString += 'Currency=' + currencyId + '&';
                SmartBoxHostedQueryString += 'Language=' + languageId + '&';
                SmartBoxHostedQueryString += 'RequestDateTime=' + trxDateTime + '&';
                SmartBoxHostedQueryString += 'PaymentViewType=' + paymentViewType + '&';

                window.addEventListener('message', function (event) {
                    // debugger;
                    var parser = document.createElement('a');
                    parser.href = SmartBoxDoamin;
                    var ua = window.navigator.userAgent;
                    //  if (event.origin === parser.origin || (parser.origin == undefined && (ua.indexOf('MSIE ') > 0|| ua.indexOf('Trident/')>0 || ua.indexOf('Edge/')>0))) {
                    if (event.data != null && event.data != '') {

                        if (event.data.callback == 'errorCallback' && this.SmartBox.Checkout.configure.errorCallback !== undefined) {
                            this.SmartBox.Checkout.configure.errorCallback(event.data);
                        }
                        else if (event.data.callback == 'completeCallback' && this.SmartBox.Checkout.configure.completeCallback !== undefined) {
                            this.SmartBox.Checkout.configure.completeCallback(event.data);
                        }
                        else if (event.data.callback == 'cancelCallback') {
                            closeSmartBox();

                            if (this.SmartBox.Checkout.configure?.cancelCallback != undefined) {
                                this.SmartBox.Checkout.configure.cancelCallback();
                            }
                        }
                    }
                    //}
                }, false);
                if (hideCloseButton) {
                    SmartBoxHostedQueryString += 'hideCloseButton=' + true + '&';
                    if (isEnableReturnUrl) {
                        //SmartBoxHostedQueryString += 'returnUrl=' + returnUrl;
                    }
                }
                return SmartBoxDoamin + '/add-payment' + SmartBoxHostedQueryString;
            }

            return "";
        }

        function getSmartBoxUrl() {
            if (this.configure) {
                return GetSmartBoxUrl(this.configure, false, false);

            } else {
                return "";
            }
        };

        // Public interface
        return {
            showSmartBox: showSmartBox,
            closeSmartBox: closeSmartBox,
            configure: configure,
            showPaymentPage: showPaymentPage,
            getSmartBoxUrl: getSmartBoxUrl
        };

    })();
})(window);
