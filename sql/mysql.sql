CREATE TABLE freecontent (
    id      INT(5) UNSIGNED      NOT NULL AUTO_INCREMENT,
    title   VARCHAR(150)         NOT NULL DEFAULT '',
    type    TINYINT(10) UNSIGNED NOT NULL DEFAULT '0',
    design  TINYINT(4) UNSIGNED  NOT NULL DEFAULT '0',
    hide    TINYINT(4) UNSIGNED  NOT NULL DEFAULT '0',
    adress  VARCHAR(255)                  DEFAULT NULL,
    comment VARCHAR(255)                  DEFAULT NULL,
    special VARCHAR(255)                  DEFAULT NULL,
    hits    INT(6) UNSIGNED      NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
)
    ENGINE = ISAM;

INSERT INTO freecontent
VALUES (1, 'Tutorial FreeContent', 0, 0, 1, 'tutorial.html', 'Tutorial', NULL, 0),
       (2, 'Le Monde (French)', 1, 1, 1, 'http://www.lemonde.fr', 'Le journal Le Monde', NULL, 0),
       (3, 'Liberation (Francais)', 1, 0, 0, 'http://www.liberation.fr', 'Liberation Journal', NULL, 0),
       (4, 'China Udn News', 1, 0, 0, 'http://udn.com/NEWS/SITEMAP_TITLE/TOPIC_Title50.shtml', 'China Times', NULL, 0),
       (5, 'wjue (Chinese) Blog', 1, 2, 0, 'http://www.wjue.org', 'My Chinese Blog', NULL, 10),
       (6, 'Google Top Stories', 1, 0, 0, 'http://news.google.com', 'From Google News', NULL, 0),
       (7, 'News about China', 1, 0, 0, 'http://news.google.com/news?hl=en&edition=us&q=china+-xinhua+-sars&btnG=Search+News', 'From Google News', NULL, 0),
       (8, 'Yahoo News', 1, 3, 0, 'http://news.yahoo.com', 'News From Yahoo', NULL, 0),
       (9, 'CNN International', 1, 0, 0, 'http://edition.cnn.com/', 'CNN', NULL, 0),
       (10, 'Asahi Shimbun', 1, 0, 0, 'http://www.asahi.com/english/english.html', 'Japan Asahi Shimbun', NULL, 0),
       (11, 'Korea Herald', 1, 4, 0, 'http://www.koreaherald.co.kr/index.asp', 'Korea Herald English', NULL, 0),
       (12, 'WashingPost', 1, 0, 0, 'http://www.washingpost.com/', 'WashingPost', NULL, 0),
       (13, 'The New York Times', 1, 6, 0, 'http://www.nytimes.com/', 'The New York Times', NULL, 0),
       (14, 'Offshore Outsourcing', 1, 0, 0, 'http://news.google.com/news?hl=en&edition=us&num=100&q=offshore+outsourcing+-india&btnG=Search+News&scoring=d', 'Outsourcing latest news', NULL, 0),
       (15, 'Herald Tribune', 1, 3, 0, 'http://www.iht.com/frontpage.html', 'Herald Tribune', NULL, 0),
       (16, 'HiTech Science', 1, 7, 0, 'http://news.google.com/news/en/us/technology.html', 'Technology & Science', NULL, 0),
       (17, 'Washington Times', 1, 0, 0, 'http://washingtontimes.com/', 'Washington Times', NULL, 0),
       (18, 'Muslim Al-Jazeerah', 1, 4, 0, 'http://www.aljazeerah.info/index.htm', 'Al-Jazeerah Info', NULL, 0),
       (19, 'StraitsTimes', 1, 1, 0, 'http://straitstimes.asia1.com.sg/', 'StraitsTimes Singapore', NULL, 0),
       (20, 'Financial Times', 1, 1, 0, 'http://news.ft.com/home/asia/', 'Financial Times', NULL, 0),
       (21, 'Entertainment', 1, 7, 0, 'http://news.google.com/news/en/uk/entertainment.html', 'Entertainment in Europe', NULL, 0),
       (22, 'Business in French', 1, 7, 0, 'http://news.google.com/news/fr/fr/business.html', 'Economie en Francais', NULL, 0),
       (23, 'Deutschland', 1, 7, 0, 'http://news.google.com/news/de/de/nation.html', 'News Deutschland', NULL, 0);

CREATE TABLE freecontent_newsticker (
    source_url VARCHAR(255) NOT NULL DEFAULT '',
    headlines  TEXT,
    updatetime INT(11)               DEFAULT NULL,
    PRIMARY KEY (source_url)
)
    ENGINE = ISAM;

INSERT INTO `freecontent_newsticker`
VALUES ('http://news.google.com/news?hl=en&edition=us&q=china+-xinhua+-sars&btnG=Search+News',
        'YToxNTp7aTowO2E6Mjp7czo0OiJsaW5rIjtzOjcwOiJodHRwOi8vd3d3LmNoaW5hZGFpbHkuY29tLmNuL2VuZ2xpc2gvZG9jLzIwMDQtMDQvMDYvY29udGVudF8zMjEwNjQuaHRtIjtzOjU6InRpdGxlIjtzOjQ5OiJXb3JsZCAmIzM5O2JlYXV0eSBtYWtlcnMmIzM5OyBrbm9ja2luZyBDaGluYSBkb29yIjt9aToxO2E6Mjp7czo0OiJsaW5rIjtzOjY4OiJodHRwOi8vZW5nbGlzaC5wZW9wbGVkYWlseS5jb20uY24vMjAwNDA0LzA0L2VuZzIwMDQwNDA0XzEzOTM4NS5zaHRtbCI7czo1OiJ0aXRsZSI7czo1MDoiQ2hpbmEmIzM5O3MgY29zbWV0aWMgaW5kdXN0cnkgZW1iYXJrcyBvbiByZXNodWZmbGUiO31pOjI7YToyOntzOjQ6ImxpbmsiO3M6NDQ6Imh0dHA6Ly9zZy5iaXoueWFob28uY29tLzA0MDQwNi8xNS8zamI0MS5odG1sIjtzOjU6InRpdGxlIjtzOjU3OiJDaGluYSBBaW1zIFRvIFN0YXJ0IEZ1ZWwgT2lsIEZ1dHVyZXMgVHJhZGUgTWlkeWVhciAtSW5kdXMiO31pOjM7YToyOntzOjQ6ImxpbmsiO3M6NDQ6Imh0dHA6Ly9zZy5iaXoueWFob28uY29tLzA0MDQwNi8xNS8zamF4Ni5odG1sIjtzOjU6InRpdGxlIjtzOjU3OiJTaW5vcGVjLEV4eG9uTW9iaWwsQXJhbWNvIFNvbHZlIE1rdCBBY2Nlc3MgRm9yIENoaW5hIFByb2oiO31pOjQ7YToyOntzOjQ6ImxpbmsiO3M6Nzc6Imh0dHA6Ly9xdW90ZS5ibG9vbWJlcmcuY29tL2FwcHMvbmV3cz9waWQ9MTAwMDAwODAmc2lkPWEwNkVtbHJmQWdfTSZyZWZlcj1hc2lhIjtzOjU6InRpdGxlIjtzOjY2OiJIU0JDIENoaW5hIEJhbmsgVW5pdCYjMzk7cyAyMDAzIFByb2ZpdCBNb3JlIFRoYW4gRG91YmxlZCAoVXBkYXRlMikiO31pOjU7YToyOntzOjQ6ImxpbmsiO3M6NDQ6Imh0dHA6Ly9zZy5iaXoueWFob28uY29tLzA0MDQwNi8xNS8zamF4NS5odG1sIjtzOjU6InRpdGxlIjtzOjUyOiJTdGFuZGFyZCBDaGFydGVyZWQgSm9pbnMgQ2hpbmEgVW5pb25QYXkgQ2FyZCBOZXR3b3JrIjt9aTo2O2E6Mjp7czo0OiJsaW5rIjtzOjg3OiJodHRwOi8vcXVvdGUuYmxvb21iZXJnLmNvbS9hcHBzL25ld3M/cGlkPTEwMDAwMTc3JnNpZD1hZ0dGeC5IYXZHdW8mcmVmZXI9bWFya2V0X2luc2lnaHQiO3M6NToidGl0bGUiO3M6Njc6IkludmVzdG9ycyBDdXQgQmV0cyBDaGluYSBXaWxsIEVhc2UgWXVhbiYjMzk7cyBQZWcgYXMgSW5mbGF0aW9uIEViYnMiO31pOjc7YToyOntzOjQ6ImxpbmsiO3M6NzY6Imh0dHA6Ly93d3cucmV1dGVycy5jb20vbmV3c0FydGljbGUuamh0bWw/dHlwZT10ZWNobm9sb2d5TmV3cyZzdG9yeUlEPTQ3NTk0NzciO3M6NToidGl0bGUiO3M6NDg6IlByZW1hdHVyZSB0byBHYXVnZSBXQVBJIEhpdCBvbiBDaGluYSBTYWxlcy1JbnRlbCI7fWk6ODthOjI6e3M6NDoibGluayI7czo2NjoiaHR0cDovL3d3dy50YWlwZWl0aW1lcy5jb20vTmV3cy9iaXovYXJjaGl2ZXMvMjAwNC8wNC8wNi8yMDAzMTE2OTAwIjtzOjU6InRpdGxlIjtzOjcwOiJJbnRlbCByZXNpc3RzIENoaW5hJiMzOTtzIFdBUEkgc3RhbmRhcmQsIHNheXMgaXQgYW1vdW50cyB0byB1bmZhaXIgLi4uIjt9aTo5O2E6Mjp7czo0OiJsaW5rIjtzOjI4OiJodHRwOi8vcDJwbmV0Lm5ldC9zdG9yeS8xMTQ0IjtzOjU6InRpdGxlIjtzOjM1OiJJbnRlbCBkaWdzIGluIG92ZXIgQ2hpbmEmIzM5O3MgV0xBTiI7fWk6MTA7YToyOntzOjQ6ImxpbmsiO3M6NDE6Imh0dHA6Ly93d3cudGhlaW5xdWlyZXIubmV0Lz9hcnRpY2xlPTE1MTc4IjtzOjU6InRpdGxlIjtzOjU4OiJJbnRlbCYjMzk7cyBDaGluYSB3aS1maSBwcm9ibGVtIGNvdWxkIGJlIG9mIGl0cyBvd24gbWFraW5nIjt9aToxMTthOjI6e3M6NDoibGluayI7czo5MjoiaHR0cDovL2Jpei50aGVzdGFyLmNvbS5teS9uZXdzL3N0b3J5LmFzcD9maWxlPS8yMDA0LzQvNi9idXNpbmVzcy8yMDA0MDQwNjE1MjIxOCZzZWM9YnVzaW5lc3MiO3M6NToidGl0bGUiO3M6NTQ6IkNoaW5hIHNheXMgbmV3IG9pbCBmaW5kcyB0byByZWR1Y2UgcmVsaWFuY2Ugb24gaW1wb3J0cyI7fWk6MTI7YToyOntzOjQ6ImxpbmsiO3M6NTk6Imh0dHA6Ly93d3cuYnVzaW5lc3NyZXBvcnQuY28uemEvaW5kZXgucGhwP2ZBcnRpY2xlSWQ9Mzk0NDY0IjtzOjU6InRpdGxlIjtzOjUyOiJDaGluYSBzYXlzIG5ldyBvaWwgZmluZCB3aWxsIHJlZHVjZSBuZWVkIGZvciBpbXBvcnRzIjt9aToxMzthOjI6e3M6NDoibGluayI7czo3MDoiaHR0cDovL3d3dy5jaGluYWRhaWx5LmNvbS5jbi9lbmdsaXNoL2RvYy8yMDA0LTA0LzA2L2NvbnRlbnRfMzIwODIxLmh0bSI7czo1OiJ0aXRsZSI7czozMzoiT2lsIGZpbmRzIHRvIGVhc2UgbmF0aW9uYWwgdGhpcnN0Ijt9aToxNDthOjI6e3M6NDoibGluayI7czo2ODoiaHR0cDovL2VuZ2xpc2gucGVvcGxlZGFpbHkuY29tLmNuLzIwMDQwNC8wNi9lbmcyMDA0MDQwNl8xMzk1OTIuc2h0bWwiO3M6NToidGl0bGUiO3M6NTg6Ik1ham9yIGNoYW5nZXMgaW4gYXV0byBpbmR1c3RyeSBzaW5jZSBDaGluYSYjMzk7cyBXVE8gZW50cnkiO319',
        1081226638);
