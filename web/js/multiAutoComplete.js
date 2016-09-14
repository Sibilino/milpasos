milpasos.multiAutoComplete = (function ($) {

    /**
     * Sorting function that sorts by the property 'label';
     * @param a
     * @param b
     * @returns {number}
     */
    var sortByLabel = function (a, b) {
        var aLabel = a.label.toLowerCase();
        var bLabel = b.label.toLowerCase();
        return ((aLabel < bLabel) ? -1 : ((aLabel > bLabel) ? 1 : 0));
    }

    return {
        /**
         * Activates the MultiAutoComplete functionality.
         * @param {string} id The id of the base Autocomplete widget input.
         * @param {string} inputName The name to be used on list inputs.
         * @param {mixed[]} initialValues The initial selection of item values.
         */
        activate: function (id, inputName, initialValues) {

            var autoComplete = $('#'+id);
            var ul = autoComplete.siblings('ul');

            /**
             * Adds an item with label and value to the Autocomplete source items.
             * @param {string} label
             * @param {mixed} value
             */
            var addToSource = function (label, value) {
                var newSource = autoComplete.autocomplete('option', 'source');
                newSource.push({
                    label: label,
                    value: value
                });
                newSource.sort(sortByLabel);
                autoComplete.autocomplete('option', 'source', newSource);
            };
            /**
             * Eliminates the item with the given value from the Autocomplete source.
             * @param {mixed} value
             */
            var removeFromSource = function (value) {
                var newSource = autoComplete.autocomplete('option', 'source');
                newSource = $.grep(newSource, function (e) {
                    return e.value != value;
                });
                newSource.sort(sortByLabel);
                autoComplete.autocomplete('option', 'source', newSource);
            };
            /**
             * Gets the label associated with this value in the Autocomplete source.
             * @param {mixed} value
             * @returns {string}
             */
            var getLabel = function (value) {
                var source = autoComplete.autocomplete('option', 'source');
                for (var i=0; i<source.length; i++) {
                    if (source[i].value == value) {
                        return source[i].label;
                    }
                }
                return 'Undefined';
            };
            /**
             * Adds an item to the selection list, and removes it from the Autocomplete source.
             * @param {mixed} value
             * @param {string} label Optional. If not given, the label will be taken from the item with the given value.
             */
            var selectItem = function (value, label) {
                if (typeof label === "undefined") {
                    label = getLabel(value);
                }
                var input = $('<input type="hidden" />')
                    .attr('name', inputName)
                    .val(value);
                var li = $('<li>')
                    .attr('data-ref', value)
                    .text(label)
                    .on('click', function () {
                        addToSource($(this).text(), $(this).attr('data-ref'));
                        $(this).remove();
                    })
                    .append(input);
                ul.append(li);
                removeFromSource(value);
            };

            for (var i=0; i<initialValues.length; i++) {
                selectItem(initialValues[i]);
            }
            
            autoComplete
                .on('autocompleteselect', function (event, ui) {
                    selectItem(ui.item.value, ui.item.label);

                }).on('click', function (event) {
                    autoComplete.autocomplete('search'); // open menu without waiting for the user typing

                }).on('focusout', function (event) {
                    autoComplete.autocomplete('close');

                }).on('autocompleteclose', function (event, ui) {
                    autoComplete.val(''); // Clear input if menu is closed (for example, after a selection)
                })
            ;
        }
    };

})(jQuery);