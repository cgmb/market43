use market43;
insert into item_type (Name, IconPath, Rarity, Description) values (
  'Fountain of Money',
  'fountain_of_money.png',
  2,
  'Receive 1000 credits per day.');
insert into item_type (Name, IconPath, Rarity, Description) values (
  'Magic Leaf',
  'leaf-1.png',
  2,
  'Receive 1 random item per day.');
insert into item_type (Name, IconPath, Rarity, Description) values (
  'Magic Leaves',
  'leaf-2.png',
  3,
  'Receive 3 random items per day.');
insert into item_type (Name, IconPath, Rarity, Description) values (
  'Living Leaves',
  'leaf-3.png',
  4,
  'Receive 10 random items per day.');
insert into recipe () values ();
insert into recipe_input (Recipe, InputItemType, InputItemCount) values (
  1,
  2,
  2);
insert into recipe_output (Recipe, OutputItemType, OutputItemCount) values (
  1,
  3,
  1);
insert into recipe () values ();
insert into recipe_input (Recipe, InputItemType, InputItemCount) values (
  2,
  3,
  3);
insert into recipe_output (Recipe, OutputItemType, OutputItemCount) values (
  2,
  4,
  1);
insert into item_type (Name, IconPath, Rarity, Description) values (
  'Well of Money',
  'fountain_of_money-2.png',
  3,
  'Receive 3000 credits per day.');
insert into recipe () values ();
insert into recipe_input (Recipe, InputItemType, InputItemCount) values (
  3,
  1,
  2);
insert into recipe_output (Recipe, OutputItemType, OutputItemCount) values (
  3,
  5,
  1);
