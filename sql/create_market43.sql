create database market43
  character set = utf8mb4
  collate = utf8mb4_unicode_ci;
use market43;
create table user (UserId int unsigned auto_increment,
  Email varbinary(254) unique not null,
  Nickname varchar(20) not null,
  AccountType tinyint not null,
  primary key (UserId));
create table item_type (ItemTypeId int unsigned auto_increment,
  Name varchar(32) not null,
  IconPath varbinary(255) not null,
  Rarity tinyint not null,
  Description text,
  primary key (ItemTypeId));
create table item (ItemId int unsigned auto_increment,
  ItemType int unsigned not null,
  OwnerUserId int unsigned not null,
  primary key (ItemId),
  foreign key (ItemType) references item_type(ItemTypeId),
  foreign key (OwnerUserId) references user(UserId));
create table recipe (RecipeId int unsigned auto_increment,
  primary key (RecipeId));
create table recipe_input (Recipe int unsigned,
  InputItemType int unsigned,
  InputItemCount tinyint unsigned,
  primary key (Recipe, InputItemType, InputItemCount),
  foreign key (Recipe) references recipe(RecipeId),
  foreign key (InputItemType) references item_type(ItemTypeId));
create table recipe_output (Recipe int unsigned,
  OutputItemType int unsigned,
  OutputItemCount tinyint unsigned,
  primary key (Recipe, OutputItemType, OutputItemCount),
  foreign key (Recipe) references recipe(RecipeId),
  foreign key (OutputItemType) references item_type(ItemTypeId));
create table listing (ListingId int unsigned auto_increment,
  MinimumBid int unsigned not null,
  PostedTimestamp timestamp not null default current_timestamp,
  ExpiryTimestamp timestamp not null,
  ListedItemId int unsigned not null,
  ListingUserId int unsigned not null,
  Open bool not null default true,
  primary key (ListingId),
  foreign key (ListedItemId) references item(ItemId),
  foreign key (ListingUserId) references user(UserId));
create table transaction (TransactionId int unsigned auto_increment,
  NetBalanceChange int not null,
  TransactionType tinyint not null,
  TransactionUser int unsigned not null,
  TransactionTimestamp timestamp not null default current_timestamp,
  primary key (TransactionId),
  foreign key (TransactionUser) references user(UserId));
create table bid (Bidder int unsigned,
  Listing int unsigned,
  Value int unsigned,
  primary key (Bidder, Listing),
  foreign key (Bidder) references user(UserId),
  foreign key (Listing) references listing(ListingId));
create table credential (SaltedHash binary(60),
  User int unsigned,
  Active bool not null,
  primary key (SaltedHash, User),
  foreign key (User) references user(UserId));
