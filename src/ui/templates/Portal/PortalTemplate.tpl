<!DOCTYPE html>
<html lang="{$pageLocale->getLang()}" dir="{$pageLocale->getDir()}">
    <head>
        <title>{plugin name='tagPageTitle'}</title>
        {plugin name='tagFavIcon'}
        {plugin name='tagPageMeta'}
        {plugin name='tagResourceLink' type='css'}
    </head>
    <body>
        {content}
        {plugin name='tagResourceLink' type='js'}
    </body>
</html>
