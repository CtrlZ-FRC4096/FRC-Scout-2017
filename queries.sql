DELETE FROM matchautos;
ALTER TABLE matchautos                 AUTO_INCREMENT=1;
DELETE FROM matchclimbs;
ALTER TABLE matchclimbs                AUTO_INCREMENT=1;
DELETE FROM matchgearfeeds;
ALTER TABLE matchgearfeeds             AUTO_INCREMENT=1;
DELETE FROM matchgears;
ALTER TABLE matchgears                 AUTO_INCREMENT=1;
DELETE FROM matchratings;
ALTER TABLE matchratings               AUTO_INCREMENT=1;
DELETE FROM matchballfeeds;
ALTER TABLE matchballfeeds             AUTO_INCREMENT=1;
DELETE FROM matchshoots;
ALTER TABLE matchshoots                AUTO_INCREMENT=1;
DELETE FROM matchballfeeds_preprocess;
ALTER TABLE matchballfeeds_preprocess  AUTO_INCREMENT=1;
DELETE FROM matchshoots_preprocess;
ALTER TABLE matchshoots_preprocess     AUTO_INCREMENT=1;
UPDATE teammatches SET deviceID = null, collectionStarted = 0, collectionEnded = 0, scouterID = null, postprocessed = 0, ready = 0;



DELETE FROM matchautos WHERE teamMatchID = 568;
DELETE FROM matchclimbs WHERE teamMatchID = 568;
DELETE FROM matchgearfeeds WHERE teamMatchID = 568;
DELETE FROM matchgears WHERE teamMatchID = 568;
DELETE FROM matchratings WHERE teamMatchID = 568;
DELETE FROM matchballfeeds_preprocess WHERE teamMatchID = 568;
DELETE FROM matchshoots_preprocess WHERE teamMatchID = 568;


DELETE FROM matchballfeeds WHERE teamMatchID = 568;
DELETE FROM matchshoots WHERE teamMatchID = 568;
DELETE FROM postprocessdiscrepancies WHERE teamMatchID = 568;
