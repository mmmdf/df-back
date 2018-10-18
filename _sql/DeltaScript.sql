ALTER TABLE reports CHANGE id id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `consolidator` (
  `id` int(11) UNSIGNED NOT NULL,
  `acronym` varchar(8) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `consolidator` (`id`, `acronym`, `name`) VALUES
(1, 'DriveFly', 'DriveFly'),
(2, 'FHR', 'FHR'),
(3, 'APH', 'AHP'),
(4, 'SKYP', 'SKYP'),
(6, 'L4P', 'Looking For Parking'),
(7, 'NEW', 'NEW'),
(16, 'CHR', 'CHR'),
(17, 'HD', 'Happy Days'),
(24, 'EX', 'EX'),
(25, 'SPF', 'SPF');

ALTER TABLE `consolidator`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `consolidator`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

-- this should also be put in place when reports table is cleaned up of inconsistent data
-- ALTER TABLE reports ADD CONSTRAINT report_consolidator FOREIGN KEY (consolidatorID) REFERENCES consolidator(id)
-- COMMIT;

CREATE TABLE `audit_trail` (
  `id` int(11) UNSIGNED NOT NULL,
  `report` int(11) UNSIGNED NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `record` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `audit_trail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE audit_trail ADD CONSTRAINT audit_trail_report FOREIGN KEY (report) REFERENCES reports(id)

CREATE TABLE `image` (
  `id` int(11) UNSIGNED NOT NULL,
  `report` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `size` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `image_report` (`report`);

ALTER TABLE `image`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `image`
  ADD CONSTRAINT `image_report` FOREIGN KEY (`report`) REFERENCES `reports` (`id`);
COMMIT;

CREATE TABLE `extra_payment` (
  `id` int(11) UNSIGNED NOT NULL,
  `report` int(11) UNSIGNED NOT NULL,
  `for` varchar(64) NOT NULL,
  `amount` int(11) UNSIGNED NOT NULL,
  `status` varchar(32) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `extra_payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `extra_payment_report` (`report`);

ALTER TABLE `extra_payment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

  ALTER TABLE `extra_payment`
  ADD CONSTRAINT `extra_payment_report` FOREIGN KEY (`report`) REFERENCES `reports` (`id`);
COMMIT;

-------------------------------------------------------------------------------------------------

CREATE TABLE alerts (
  id int(11) UNSIGNED NOT NULL,
  carReg varchar(25) NOT NULL,
  email varchar(32) NOT NULL,
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE alerts
  ADD PRIMARY KEY (id);

ALTER TABLE alerts
  MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;