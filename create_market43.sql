create database market43 character set = utf8mb4 collate = utf8mb4_unicode_ci;
use market43;
create table user (UserId int unsigned auto_increment, Email varbinary(254) unique not null, Nickname varchar(20) not null, AccountType tinyint not null, primary key (UserId));
create table item_type (Name varchar(32) not null, IconPath varbinary(255) not null, rarity tinyint not null, description text, primary key (Name));
create table item (ItemId int unsigned auto_increment, ItemTypeName varchar(32), OwnerUserId int unsigned, primary key (ItemId), foreign key (ItemTypeName) references item_type(Name), foreign key (OwnerUserId) references user(UserId));
create table recipe (InputItemType varchar(32), InputItemCount tinyint, OutputItemType varchar(32), foreign key (InputItemType) references item_type(Name), primary key (InputItemType, InputItemCount, OutputItemType), foreign key (OutputItemType) references item_type(Name));
create table listing (ListingId int unsigned auto_increment, MinimumBid int unsigned, PostedTimestamp timestamp, ExpiryTimestamp timestamp, ListedItemId int unsigned, ListingUserId int unsigned, primary key (ListingId), foreign key (ListedItemId) references item(ItemId), foreign key (ListingUserId) references user(UserId));
create table transaction (TransactionId int unsigned, NetBalanceChange int, TransactionType tinyint, TransactionUser int unsigned, primary key (TransactionId), foreign key (TransactionUser) references user(UserId));
create table bid (Bidder int unsigned, Listing int unsigned, Value int unsigned, primary key (Bidder, Listing, Value), foreign key (Bidder) references user(UserId), foreign key (Listing) references listing(ListingId));
create table credential (SaltedHash binary(60), User int unsigned, Active bool, primary key (SaltedHash, User), foreign key (User) references user(UserId));

