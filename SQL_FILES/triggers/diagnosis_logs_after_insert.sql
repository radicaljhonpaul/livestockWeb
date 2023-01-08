CREATE DEFINER=`root`@`localhost` TRIGGER `livestockweb`.`diagnosis_logs_AFTER_INSERT` AFTER INSERT ON `diagnosis_logs` FOR EACH ROW
BEGIN
INSERT INTO visit_logs
 SET visit_date = NEW.visit_date,
     livestock_id = NEW.livestock_id,
     farmer_id = (select farmer_id from livestocks where id = NEW.livestock_id),
     assigned_to = NEW.assigned_to,
     diagnosis_log_id = NEW.id;
END