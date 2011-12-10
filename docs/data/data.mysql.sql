INSERT INTO `task` (`id`, `text`, `created`, `done`, `user_id`, `tasklist_id`) VALUES
(1,	'Provést analýzu',	'2011-12-06 12:30:00',	1,	2,	1),
(2,	'Implementace úkolníčku',	'2011-12-06 12:35:50',	0,	3,	1),
(3,	'Sepsání dokumentace',	'2011-12-07 16:23:30',	0,	2,	1),
(4,	'Opravit chybu #42',	'2011-12-10 16:10:40',	0,	3,	2),
(5,	'Zavolat klientovi',	'2011-12-10 17:44:32',	0,	2,	2);

INSERT INTO `tasklist` (`id`, `title`) VALUES
(1,	'Projekt A'),
(2,	'Projekt B');

INSERT INTO `user` (`id`, `username`, `password`, `name`) VALUES
(1,	'admin',	'c7ad44cbad762a5da0a452f9e854fdc1e0e7a52a38015f23f3eab1d80b931dd472634dfac71cd34ebc35d16ab7fb8a90c81f975113d6c7538dc69dd8de9077ec',	'Administrátor'),
(2,	'pepa',	'decffc27874ea35eb06aa728dd2b9a77197580e7456668ae90aa8db229e59ba4e6aac7b8d5fc1a7dcaee9cee1455044f1396e1cf1f50536604881138d0bea5e9',	'Josef Novák'),
(3,	'franta',	'124fff6f6790abdcf629a04108af221614f883f18fced9f14268abdd5465d077f2fed7d620e370ca20dd392fa05dd357dd3d45fa37f226d7621852eec3326f82',	'František Novotný');