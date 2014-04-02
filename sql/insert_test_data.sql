use market43;
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

# note: enum TransactionType { TRADE = 0, CREDIT = 1, CRAFT = 2 }
insert into transaction (NetBalanceChange, TransactionType, TransactionUser) values (
  100,
  1,
  1);


# new user with password:
# trustno1
# bcrypt 8 rounds
insert into user (Email, Nickname, AccountType) values (
  'billy@example.com',
  'bob',
  0);
insert into credential (SaltedHash, User, Active) values (
  '$2a$08$pwsPVEcHwPFDhlN.U7uueOiOuhMWbVQcLtT3LP5DC/iWj5YFwfWp6',
  2,
  true);
