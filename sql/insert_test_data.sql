use market43;

# notes:
# enum AccountType { USER = 0; ADMIN = 1 }
# enum TransactionType { TRADE = 0, CREDIT = 1, CRAFT = 2 }
# passwords use bcrypt /w 8 rounds

# user 1
insert into user (Email, Nickname, AccountType) values (
  'fred@example.com',
  'freddie',
  0);
# veryweakpassword
insert into credential (SaltedHash, User) values (
  '$2a$08$ch19z6jPgjPuopXkpL1j6eM6kjMxx17p/QQz/D/VwAIlMQhrPdqiC',
  1);
insert into item (ItemType, OwnerUserId) values (
  1,
  1);
insert into item (ItemType, OwnerUserId) values (
  2,
  1);

insert into transaction (NetBalanceChange, TransactionType, TransactionUser) values (
  100,
  1,
  1);
insert into transaction (NetBalanceChange, TransactionType, TransactionUser) values (
  -50,
  2,
  1);

#user 2
insert into user (Email, Nickname, AccountType) values (
  'billy@example.com',
  'bob',
  0);
# trustno1
insert into credential (SaltedHash, User) values (
  '$2a$08$pwsPVEcHwPFDhlN.U7uueOiOuhMWbVQcLtT3LP5DC/iWj5YFwfWp6',
  2);
insert into transaction (NetBalanceChange, TransactionType, TransactionUser) values (
  500,
  1,
  2);

#user 3 (admin)
insert into user (Email, Nickname, AccountType) values (
  'admin@example.com',
  'addy',
  1);
# password
insert into credential (SaltedHash, User) values (
  '$2a$08$RPGOkh71gYCZqZb1CRIgD.5Lm648qUsJLpUaBYr8fTh9u1HOD2Nsy',
  3);

# user 4
insert into user (Email, Nickname, AccountType) values (
  'alice@example.com',
  'Big Al',
  0);
# 123456
insert into credential (SaltedHash, User) values (
  '$2a$08$z/S30rGGi7lm4DPMKz/S7O/GxvfEQsx7zRgjjZPukHjrF1ND0y9oq',
  4);
insert into transaction (NetBalanceChange, TransactionType, TransactionUser) values (
  300,
  1,
  4);

insert into listing (MinimumBid, PostedTimestamp, ExpiryTimestamp,
ListedItemId, ListingUserId) values (
  10,
  '2014-04-01 08:12:05',
  '2014-04-08 08:12:05',
  1,
  1);
insert into bid (Bidder, Listing, Value) values (
  2,
  1,
  55);
insert into bid (Bidder, Listing, Value) values (
  4,
  1,
  60);
