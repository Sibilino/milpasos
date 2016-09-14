milpasos.multiAutoComplete = (function ($) {
    return {
        construct: function (id, inputName) {

            var autoComplete = $('#'+id);
            var ul = autoComplete.siblings('ul');

            var addToSource = function (label, value) {
                var source = autoComplete.autocomplete('option', 'source');
                source.push({
                    label: label,
                    value: value
                });
                autoComplete.autocomplete('option', 'source', source);
            };
            var removeFromSource = function (value) {
                var newSource = autoComplete.autocomplete('option', 'source');
                newSource = $.grep(newSource, function (e) {
                    return e.value != value;
                });
                autoComplete.autocomplete('option', 'source', newSource);
            };
            var addLi = function (label, value) {
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
            };
            
            autoComplete
                .on('autocompleteselect', function (event, ui) {
                    addLi(ui.item.label, ui.item.value);
                    removeFromSource(ui.item.value);

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