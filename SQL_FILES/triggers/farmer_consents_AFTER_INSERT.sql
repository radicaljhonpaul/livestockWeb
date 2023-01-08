CREATE DEFINER=`root`@`localhost` TRIGGER `livestockweb`.`farmer_consents_AFTER_INSERT` AFTER INSERT ON `farmer_consents` FOR EACH ROW
BEGIN

SET SQL_SAFE_UPDATES=0;

set @mobile = new.details;
set @mobile_clean = REPLACE(@mobile, N'+63', N'');

IF (new.type = 'sms') THEN
	UPDATE farmers
	SET sms_consent = new.consent,
		sms_consent_date = new.created_at,
		sms_consent_method = new.method
	WHERE farmers.mobile_number = @mobile_clean;
END IF;

IF (new.type = 'program') THEN
	UPDATE farmers
	SET program_consent = new.consent,
		program_consent_date = new.created_at,
		program_consent_method = new.method
	WHERE farmers.mobile_number = @mobile_clean;
END IF;

SET SQL_SAFE_UPDATES=1;

END