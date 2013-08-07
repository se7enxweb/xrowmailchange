{'A request was made to change your email to "%mail".'|i18n('extension/xrowmailchange', '', hash( '%mail', $new_mail ))}

{'Click the following link to confirm your new email address'|i18n('extension/xrowmailchange')}:
http://{$hostname}{concat( '/mailchange/confirmation/', $hash )|ezurl(no)}

{'If you did not request the email change or want to revert it then you have to click this link'|i18n('extension/xrowmailchange')}:
http://{$hostname}{concat( '/mailchange/rejection/', $hash )|ezurl(no)}