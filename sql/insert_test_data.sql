# note: enum AccountType { USER = 0; ADMIN = 1 }

# new user with password:
# veryweakpassword
# bcrypt 8 rounds
insert into user (Email, Nickname, AccountType) values (
  'fred@example.com',
  'freddie',
  0);
insert into credential (SaltedHash, User, Active) values (
  '$2a$08$ch19z6jPgjPuopXkpL1j6eM6kjMxx17p/QQz/D/VwAIlMQhrPdqiC',
  1,
  true);
insert into item (ItemType, OwnerUserId) values (
  1,
  1);
