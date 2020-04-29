<table>
    <tr>
        <th><{$smarty.const._AM_YOGURT_TITLE}></th>
        <th><{$smarty.const._AM_YOGURT_DESCRIPTION}></th>
        <th><{$smarty.const._AM_YOGURT_WEIGHT}></th>
        <th><{$smarty.const._AM_YOGURT_ACTION}></th>
    </tr>
    <{foreach item=category from=$categories}>
        <tr class="<{cycle values='odd, even'}>">
            <td><{$category.cat_title}></td>
            <td><{$category.cat_description}></td>
            <td align="center"><{$category.cat_weight}></td>
            <td align="center">
                <a href="fieldscategory.php?id=<{$category.cat_id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>"
                                                                                                                  alt="<{$smarty.const._EDIT}>"
                                                                                                                  title="<{$smarty.const._EDIT}>"/></a>
                &nbsp;<a href="fieldscategory.php?op=delete&amp;id=<{$category.cat_id}>" title="<{$smarty.const._DELETE}>"><img
                            src="<{xoModuleIcons16 delete.png}>" alt="<{$smarty.const._DELETE}>" title="<{$smarty.const._DELETE}>"</a>
            </td>
        </tr>
    <{/foreach}>
</table>
