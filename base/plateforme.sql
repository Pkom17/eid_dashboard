insert into plateforme(id,site_id,name,lab_desc,lab_location) values(1,NULL,'RetroCI','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(2,NULL,'IPCI','','ADIOPODOUME');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(3,NULL,'CHU YOPOUGON','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(4,NULL,'CEPREF','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(5,NULL,'INSP','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(6,NULL,'CEDRES','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(7,NULL,'CHR YAMOUSSOUKRO','','YAMOUSSOUKRO');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(8,NULL,'CHR ABENGOUROU','','ABENGOUROU');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(9,NULL,'CHU BOUAKE','','BOUAKE');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(10,NULL,'CHR KORHOGO','','KORHOGO');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(11,NULL,'HG GAGNOA','','GAGNOA');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(12,NULL,'HG ABOBO SUD','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(13,NULL,'HG SOUBRE','','SOUBRE');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(14,NULL,'CHR MAN','','MAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(15,NULL,'CHR SAN PEDRO','','SAN-PEDRO');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(16,NULL,'CIRBA','','ABIDJAN');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(17,NULL,'CHR GAGNOA','','GAGNOA');
insert into plateforme(id,site_id,name,lab_desc,lab_location) values(18,NULL,'CHU ANGRE','','ABIDJAN');

-- alter table plateforme add column eid_active boolean default true;
update plateforme set eid_active = 0 where id in (2,5,11,13,16);