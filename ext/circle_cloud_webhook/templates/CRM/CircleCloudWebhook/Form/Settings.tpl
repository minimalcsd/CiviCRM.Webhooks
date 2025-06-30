<div class="crm-block crm-form-block">
  {* HEADER *}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  {foreach from=$elementNames item=elementName}
    <div class="crm-section">
      <div class="label">{$form.circle_cloud_webhook_settings.$elementName.label}</div>
      <div class="content">
        {$form.circle_cloud_webhook_settings.$elementName.html}
        {if $help.$elementName}
          </br>
          <span class="description">{$help.$elementName}<span>
        {/if}
      </div>
      <div class="clear"></div>
    </div>
  {/foreach}

  {* FOOTER *}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>
