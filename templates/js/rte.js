$.each({
    // Useful info about mouse clicking bug in jQuery UI:
    // http://stackoverflow.com/questions/6300683/jquery-ui-autocomplete-value-after-mouse-click-is-old-value
    // http://stackoverflow.com/questions/7315556/jquery-ui-autocomplete-select-event-not-working-with-mouse-click
    // http://jqueryui.com/demos/autocomplete/#events (check focus and select events)

    createRTE: function(options){

        var q=this.jquery;

        this.jquery.elrte($.extend({
                fmOpen: function(callback) {
                            if (typeof dialog != undefined) {
                            // create new elFinder
                                dialog = $('<div />').dialogelfinder({
                                    url : 'templates/js/elfinder/php/connector.php',
                                    modal: true,
                                    commandsOptions: {
                                        getfile: {
                                            oncomplete : 'close' // close/hide elFinder
                                        }
                                },
                                getFileCallback: callback // pass callback to file manager
                                });
                            } else {
                            // reopen elFinder
                                dialog.dialogelfinder('open')
                            }
                        }
        },options))

    }

},$.univ._import);
