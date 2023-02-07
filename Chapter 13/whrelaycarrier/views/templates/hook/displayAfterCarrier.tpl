<div id="whrelaycarrier_points">
  <div>
    <span>x</span>
    <h2>{l s="Relay points" d="Modules.Whrelaycarrier.Shop"}</h2>
    <ul>
    {foreach from=$relays item=relay}
      <li data-id-relay="{$relay["id_relay"]}" {if !empty($id_relay_checked) && $id_relay_checked == $relay["id_relay"]}class="active"{/if}>{$relay["name"]}</li>
    {/foreach}
    </ul>
    <a id="whrelaycarrier_submit" class="btn btn-success">{l s="Save relay" d="Modules.Whrelaycarrier.Shop"}</a>
  </div>
</div>
