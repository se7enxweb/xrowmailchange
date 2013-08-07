<div id="mail_rejection">
    <h1>{'Stop mail change process'|i18n('extension/xrowmailchange')}</h1>
    {if is_set($error)}
        <strong>{'Error'|i18n('extension/xrowmailchange')}:</strong> {'There is no existing email change request which you can decline.'|i18n('extension/xrowmailchange')}
    {else}
        {'Your email change was successfully canceled.'|i18n('extension/xrowmailchange')}
    {/if}
</div>