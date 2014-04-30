<form action="<?php echo home_url("/"); ?>" method="get" class="search">
    <fieldset>
        <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
        <input type="image" alt="Search" src="<?php bloginfo( 'template_url' ); ?>/theme/images/icon-search.png" />
    </fieldset>
</form>