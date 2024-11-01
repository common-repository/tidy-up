function tidy (id, type, input, output)
{
  new Ajax.Updater ('tidy_' + id, wp_base + '?id=' + id + '&cmd=tidy&type=' + type + '&input=' + input + '&output=' +  output,
        {
          onLoading: function(reqest) { $('tidyc_' + id).innerHTML = wp_loading},
        });
  return false;
}

function edit (id, type, input, output)
{
  new Ajax.Updater ('tidy_' + id, wp_base + '?id=' + id + '&cmd=edit&type=' + type + '&input=' + input + '&output=' +  output,
        {
          onLoading: function(reqest) { $('tidyc_' + id).innerHTML = wp_loading},
        });
  return false;
}

function show_it (id, type, input, output)
{
  new Ajax.Updater ('tidy_' + id, wp_base + '?id=' + id + '&cmd=show&type=' + type + '&input=' + input + '&output=' +  output,
        {
          onLoading: function(reqest) { $('tidyc_' + id).innerHTML = wp_loading},
        });
  return false;
}

function save_it (ob, id, type, input, output)
{
  new Ajax.Updater ('tidy_' + id, wp_base + '?id=' + id + '&cmd=save&type=' + type + '&input=' + input + '&output=' +  output,
        {
          parameters: Form.serialize(ob),
          onLoading: function(reqest) { $('tidyc_' + id).innerHTML = wp_loading},
        });
  return false;
}