
CREATE DATABASE IF NOT EXISTS `tildakallstrom_sekattblogg` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tildakallstrom_sekattblogg`;

-- --------------------------------------------------------

--
-- Tabellstruktur `alert`
--

CREATE TABLE `alert` (
  `id` int(11) NOT NULL,
  `commentid` int(11) NOT NULL,
  `user` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------

--
-- Tabellstruktur `blogposts`
--

CREATE TABLE `blogposts` (
  `postid` int(11) NOT NULL,
  `author` varchar(128) NOT NULL,
  `authorid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `img` varchar(200) DEFAULT NULL,
  `countcomments` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Tabellstruktur `cat`
--

CREATE TABLE `cat` (
  `catid` int(11) NOT NULL,
  `userid` varchar(128) NOT NULL,
  `ownerid` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `catimg` varchar(68) NOT NULL,
  `birth` varchar(168) NOT NULL,
  `mother` varchar(128) NOT NULL,
  `father` varchar(128) NOT NULL,
  `merits` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Tabellstruktur `comments`
--

CREATE TABLE `comments` (
  `commentid` int(11) NOT NULL,
  `user` varchar(128) NOT NULL,
  `postid` int(11) NOT NULL,
  `message` text NOT NULL,
  `commented` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Trigger `comments`
--
DELIMITER $$
CREATE TRIGGER `addcomm` AFTER INSERT ON `comments` FOR EACH ROW UPDATE blogposts
SET countcomments = countcomments +1
WHERE new.postid = blogposts.postid
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `alert` AFTER INSERT ON `comments` FOR EACH ROW INSERT into alert(commentid, user) VALUES(new.commentid, new.user)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `deletecom` AFTER DELETE ON `comments` FOR EACH ROW UPDATE blogposts
SET countcomments = countcomments -1
WHERE old.postid = blogposts.postid
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur `following`
--

CREATE TABLE `following` (
  `fid` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `followerid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Trigger `following`
--
DELIMITER $$
CREATE TRIGGER `addfollower` AFTER INSERT ON `following` FOR EACH ROW UPDATE user
SET followers = followers +1
WHERE new.followerid = user.id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `delfollower` AFTER DELETE ON `following` FOR EACH ROW UPDATE user
SET followers = followers -1
WHERE old.followerid = user.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellstruktur `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `profile` text DEFAULT NULL,
  `profileimg` varchar(128) NOT NULL DEFAULT 'profiledefault.png',
  `followers` int(11) NOT NULL DEFAULT 0,
  `ucreated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



--
-- Index för tabell `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `blogposts`
--
ALTER TABLE `blogposts`
  ADD PRIMARY KEY (`postid`),
  ADD KEY `authorid` (`authorid`);

--
-- Index för tabell `cat`
--
ALTER TABLE `cat`
  ADD PRIMARY KEY (`catid`);

--
-- Index för tabell `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentid`);

--
-- Index för tabell `following`
--
ALTER TABLE `following`
  ADD PRIMARY KEY (`fid`);

--
-- Index för tabell `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);



--
-- AUTO_INCREMENT för tabell `alert`
--
ALTER TABLE `alert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT för tabell `blogposts`
--
ALTER TABLE `blogposts`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT för tabell `cat`
--
ALTER TABLE `cat`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT för tabell `comments`
--
ALTER TABLE `comments`
  MODIFY `commentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT för tabell `following`
--
ALTER TABLE `following`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT för tabell `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;



--
-- Restriktioner för tabell `blogposts`
--
ALTER TABLE `blogposts`
  ADD CONSTRAINT `blogposts_ibfk_1` FOREIGN KEY (`authorid`) REFERENCES `user` (`id`);
COMMIT;
