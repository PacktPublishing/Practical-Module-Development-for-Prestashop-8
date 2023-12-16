{extends file='page.tpl'}

{block name='page_title'}
  {$title}
{/block}
{block name='page_content'}
  <div style="display: flex; flex-wrap: wrap; justify-content: space-around;">
  {foreach from=$posts item=post}
    {include file="{$module_dir}/views/templates/front/post_mini.tpl" post=$post}
  {/foreach}
  </div>
{/block}
