function initPagination(page_size) {
    $('#pagination-container').pagination({
        dataSource: function(done) {
            $.ajax({
                type: 'GET',
                url: '/admin/subscriber_list',
                success: function(response) {
                    done(response);
                }
            });
        },
        ulClassName: 'pagination pagination-sm',
        pageSize: page_size,
        callback: function(data, pagination) {
            // template method of yourself
            var html = simpleTemplating(data);
            $('#data-container').html(html);
            $('#pagination-container').css('text-align','right');
        }
    });
}
function simpleTemplating(data) {
    var html = '<table>\
    <tr>\
        <th style="width:10%">ID</th>\
        <th style="width:20%">Subscriber Name</th>\
        <th style="width:20%">Email</th>\
        <th style="width:10%">Phone</th>\
        <th style="width:10%">Type</th>\
        <th style="width:20%">URL</th>\
    </tr>';
    $.each(data, function(index, item){
        html += '<tr>\
            <td style="width:10%">'+item.id+'</td>\
            <td style="width:10%">'+item.name+'</td>\
            <td style="width:10%">'+item.email+'</td>\
            <td style="width:10%">'+item.phone+'</td>\
            <td style="width:10%">'+item.type+'</td>\
            <td style="width:10%">'+item.url+'</td>\
        </tr>';
    });
    html += '</table>';
    return html;
}
