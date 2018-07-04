--
-- Структура таблицы `site_users`
--

CREATE TABLE `site_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_insert` datetime NOT NULL,
  `date_login` datetime NOT NULL,
  `date_action` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `site_users_friends`
--

CREATE TABLE `site_users_friends` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_friend` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL COMMENT '1-мой запрос, 2-друзья'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `site_users_messages`
--

CREATE TABLE `site_users_messages` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_friend` int(11) NOT NULL,
  `friend_read` tinyint(1) NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `time` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `site_users`
--
ALTER TABLE `site_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `site_users_friends`
--
ALTER TABLE `site_users_friends`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `site_users_messages`
--
ALTER TABLE `site_users_messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `site_users`
--
ALTER TABLE `site_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `site_users_friends`
--
ALTER TABLE `site_users_friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `site_users_messages`
--
ALTER TABLE `site_users_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
