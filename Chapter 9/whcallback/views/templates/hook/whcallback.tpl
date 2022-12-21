<div id="whcallback_container">
  <span>{l s="Any question? We can call you back!"  mod="whcallback"}</span>
  {if isset($alert_message) || isset($success_message)}
    <p class="alert {if isset($alert_message)}alert-warning{else}alert-success{/if}">
      {if isset($alert_message)}
        {$alert_message}
      {else}
        {if isset($success_message)}
          {$success_message}
        {/if}
      {/if}
    </p>
  {else}
  <p>{l s="Please leave us your details and we call you back" mod="whcallback"} {if $hours>0}{l s="in less than" mod="whcallback"} {$hours}{l s="hour(s)" mod="whcallback"}{else}{l s="as soon as possible!" mod="whcallback"}{/if}</p>
  {/if}
  <form action="" method="POST">
    <label for="whcbfirstname">{l s="Firstname" mod="whcallback"}
      <input type="text" name="whcbfirstname" id="whcbfirstname"/>
    </label>
    <label for="whcblastname">{l s="Lastname" mod="whcallback"}
      <input type="text" name="whcblastname" id="whcblastname"/>
    </label>
    <label for="whcbphone">{l s="Phone number" mod="whcallback"}
      <input type="text" name="whcbphone" id="whcbphone"/>
    </label>
    <div>
    {hook h='displayGDPRConsent' mod='psgdpr' id_module=$id_module}
    </div>
    <div class="break"></div>
    <input type="submit" name="whcbsubmit" value="{l s='Send' mod='whcallback'}" class="btn btn-primary"/>
  </form>
</div>
