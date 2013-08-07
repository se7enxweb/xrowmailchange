<div id="mail_rejection">
    <h1>{'Email change confirmation'|i18n('extension/xrowmailchange')}</h1>
    {if is_set($error)}
        <strong>{'Error'|i18n('extension/xrowmailchange')}:</strong> {'There is no existing email change request or it was already approved.'|i18n('extension/xrowmailchange')}
    {else}
        {'Your email was successfully changed.'|i18n('extension/xrowmailchange')}
    {/if}
</div>