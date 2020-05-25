function ExcerptMore(element)
{
    if(!(this instanceof ExcerptMore))
    {
        return new ExcerptMore();
    }

    let className = {
        limit: "__limit",
        read_more: '__read_more'
    };

    this.length = 50;

    function Cliked()
    {
        element.getElementsByClassName(className.limit)[0].classList.toggle('close');
    }

    this.el_read_more = function(title = 'Read more')
    {
        let readMore = document.createElement('span');
         readMore.classList.add(className.read_more);
         readMore.textContent = title;
        element.appendChild(readMore);

        return readMore;
    }

    this.el_close = function(title)
    {
        return '<span class="'+ className.limit +' close">'+ title +'</span>&#8230;&nbsp;';
    }

    this.pruning = (text) =>
    {
        text = text.toString();
        element.innerHTML = '';
        let textLength = text.length;

        if(this.length >= textLength)
        {
            return null;
        }

        element.innerHTML = text.substring(0, this.length) + this.el_close( text.substring(this.length, textLength) );

        (this.el_read_more() ).addEventListener('click', Cliked);
    }
}

function TestLength(items)
{
    if ( 'length' in items && 0 >= items.length) { return null; }

    [...items].forEach( item =>
    {
        let excerpt_more = new ExcerptMore(item);

        if('textlength' in item.dataset)
        {
            excerpt_more.length = item.dataset.textlength;
        }
        let el_btn = item.lastChild.innerHTML;
        excerpt_more.pruning( item.firstChild.textContent.split('[...]').shift() );
        if(el_btn)
        {
            item.insertAdjacentHTML('beforeend', "<br>" + el_btn);
        }
    });
}
jQuery(function($){

    TestLength( $('[data-textlength]') );

    let current_page = [];
    $('.js-more-posts').click(function(ev){
        let button = $(this);

        function Data()
        {
            if(button.data('type'))
            {
                this.post_type = button.data('type');
            }
            current_page[this.post_type] = current_page[this.post_type] ? ++current_page[this.post_type] : 2;

            this.action = 'loadmore';
            this.page = current_page[this.post_type];
            this.nonce_code = window.realEstate_main.ajax_nonce;
        }
        
        let data = new Data();

        if(!data)
        {
            return null;
        }

        $.ajax({
            url: window.realEstate_main.ajax_url,
            data: data,
            type: 'POST',
            success: function( data )
            {
                if( true === data.success)
                {
                    $(".js-section__post > div:last-child").after(data.data.posts);
                    TestLength( $('[data-textlength]') );
                }
            }
        });

    });

});

