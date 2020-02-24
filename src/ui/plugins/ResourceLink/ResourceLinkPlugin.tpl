{if $pluginModel->type eq 'css' and $pluginModel->link ne ''}
    <link rel="stylesheet" href="{$portalURL}{$pluginModel->link}">
{elseif $pluginModel->type eq 'js' and $pluginModel->link ne ''}
    <script type="text/javascript" src="{$portalURL}{$pluginModel->link }"></script>
{/if}
