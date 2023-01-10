{extends file='page.tpl'}

{block name='page_title'}
  {$title[$language.id]}
{/block}

{block name='page_content'}
  {if ($filename!="")}
  <img src="/modules/whblog/views/img/uploads/{$filename}" style="float:left; margin: 0px 20px 20px 0px; max-width: 300px; max-height: 200px;"/>
  {/if}
  {$content[$language.id]}
  <hr/>
  <strong>{l s="Categorie(s)" d="Modules.Whblog.Shop"}</strong><br/>
  {foreach from=$categories item=category}
  <a href="{$category.url}">{$category.title}</a><br/>
  {/foreach}
{/block}
