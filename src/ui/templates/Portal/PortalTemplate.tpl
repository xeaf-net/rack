<!DOCTYPE html>
<html lang="{$pageLocale->getLang()}" dir="{$pageLocale->getDir()}">
    <head>
        <title>{tag name='pageTitle'}</title>
        {tag name='favIcon'}
        {tag name='pageMeta'}
        {tag name='resourceLink' type='css'}
    </head>
    <body>
        {content}
        {tag name='resourceLink' type='js'}
    </body>
</html>
