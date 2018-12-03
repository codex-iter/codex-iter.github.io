$(document).ready(function(){
    //prepend top tab navigation
    $('#section_0').prepend('<ul class="nav nav-tabs" id="docxpressoTabBar"></ul>');

    tabNames = [];
    $('table[data-table-name]').each(function(){
        name = $(this).attr("data-table-name");
        tabNames.push(name);
        $(this).css('display', 'none');
    });
    console.log(tabNames);
    tabnum = tabNames.length;
    for (nt = 0; nt < tabnum; nt++) {
        tabnode = '<li ';
        if (nt == 0) {
                tabnode += 'class="active"';
        }
        tabnode += '><a data-toggle-tab="tab" href="' + tabNames[nt] + '"> ';
        tabnode += tabNames[nt];
        tabnode += '</a></li>';
        $('#docxpressoTabBar').append(tabnode);
    }
    //control now content visibility;
    function hideTabContent () {
        $('table[data-table-name]').each(function(){
                $(this).css('display', 'none');
                $('a[data-toggle-tab="tab"]').parent().removeClass('active');
        });
    }
    function showTabContent (tabname) {
        $('table[data-table-name="' + tabname + '"]').each(function(){
                hideTabContent();
                $(this).css('display', 'table');
                $('a[href="' + tabname + '"]').parent().addClass('active');
        });
    }
    //show first tab content
    showTabContent(tabNames[0]);
    //add click functionality
    $('a[data-toggle-tab="tab"]').click(function(){
            tabname = $(this).attr('href');
            showTabContent(tabname);
            return false;
    });
            
});


