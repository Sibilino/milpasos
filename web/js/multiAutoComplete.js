milpasos.multiAutoComplete = (function ($) {
    var sortByLabel = function (a, b) {
        var aLabel = a.label.toLowerCase();
        var bLabel = b.label.toLowerCase();
        return ((aLabel < bLabel) ? -1 : ((aLabel > bLabel) ? 1 : 0));
    }

    return {
        construct: function (id, inputName, initialValues) {

            var autoComplete = $('#'+id);
            var ul = autoComplete.siblings('ul');

            var addToSource = function (label, value) {
                var newSource = autoComplete.autocomplete('option', 'source');
                newSource.push({
                    label: label,
                    value: value
                });
                newSource.sort(sortByLabel);
                autoComplete.autocomplete('option', 'source', newSource);
            };
            var removeFromSource = function (value) {
                var newSource = autoComplete.autocomplete('option', 'source');
                newSource = $.grep(newSource, function (e) {
                    return e.value != value;
                });
                newSource.sort(sortByLabel);
                autoComplete.autocomplete('option', 'source', newSource);
            };
            var getLabel = function (value) {
                var source = autoComplete.autocomplete('option', 'source');
                for (var i=0; i<source.length; i++) {
                    if (source[i].value == value) {
                        return source[i].label;
                    }
                }
                return 'Undefined';
            };
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
                    autoComplete.autocomplete('search'); // open menu

                }).on('focusout', function (event) {
                    autoComplete.autocomplete('close');

                }).on('autocompleteclose', function (event, ui) {
                    autoComplete.val('');
                })
            ;
        }
    };

})(jQuery);