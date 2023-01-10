<div style="width: 33%;margin-top: 20px;">
  {if ($post->getFilename() != "")}
  <img src="/modules/whblog/views/img/uploads/{$post->getFilename()}" style="margin-bottom: 20px; height: 150px; width: 100%;"/>
  {/if}
  <h2>{$post->getPostLangByLangId($language.id)->getTitle()}</h2>
  <a href="{$urls.base_url}{$language.iso_code}/module/whblog/post?id_post={$post->getId()}">+ {l s="Read the full post" d="Modules.Whblog.Shop"}</a>
</div>
