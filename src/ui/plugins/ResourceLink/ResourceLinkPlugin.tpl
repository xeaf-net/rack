{if $pluginModel->type eq 'css'}
    {foreach $pluginModel->links as $link}
        <link rel="stylesheet" href="{$link}">
    {/foreach}
{elseif $pluginModel->type eq 'js'}
    {foreach $pluginModel->links as $link}
        <script type="text/javascript" src="{$link}"></script>
    {/foreach}
{/if}
