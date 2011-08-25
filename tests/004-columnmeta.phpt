--TEST--
Test column metadata
--SKIPIF--
<?php require_once(dirname(__FILE__) . '/skipif.inc'); ?>
--FILE--
<?php
require_once(dirname(__FILE__) . '/config.inc');

$db = new PDO($dsn);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
	$db->exec ("DROP KEYSPACE {$keyspace}");
} catch (PDOException $e) {}

$uuid = '5bafc990-ceb7-11e0-bd10-aa2e4924019b';

$db->exec ("CREATE KEYSPACE {$keyspace} with strategy_class = 'SimpleStrategy' and strategy_options:replication_factor=1;");
$db->exec ("USE {$keyspace}");
$db->exec ("CREATE COLUMNFAMILY types_test(
				my_key text PRIMARY KEY,
				my_bytea bytea,
				my_ascii ascii,
				my_text text,
				my_varchar varchar,
				my_uuid uuid,
				my_varint varint,
				my_int int,
				my_bigint bigint)");


$stmt = $db->prepare ("INSERT INTO types_test(my_key, my_bytea, my_ascii, my_text, my_varchar, my_uuid, my_varint, my_int, my_bigint)
									VALUES   (:key,   :bytea,   :ascii,   :text,   :varchar,   :uuid,   :varint,   :int,   :bigint)");

$stmt->getColumnMeta (0);

$stmt->bindValue (':key', "hello key");
$stmt->bindValue (':bytea', "74686520616e73776572206973203432");
$stmt->bindValue (':ascii', "hello ascii");
$stmt->bindValue (':text', "what else than lorem ipsum? well, ∆∆∆");
$stmt->bindValue (':varchar', "what else than more lorem ipsum?");
$stmt->bindValue (':uuid', '5bafc990-ceb7-11e0-bd10-aa2e4924019b');
$stmt->bindValue (':varint', 123, PDO::PARAM_INT);
$stmt->bindValue (':int', 4567, PDO::PARAM_INT);
$stmt->bindValue (':bigint', 891011, PDO::PARAM_INT);
$stmt->execute ();

$stmt = $db->query ("SELECT my_key, my_bytea, my_ascii, my_text, my_varchar, my_uuid, my_varint, my_int, my_bigint FROM types_test");
$data = $stmt->fetchAll ();

for ($i = 0; $i < 9; $i++)
{
	var_dump ($stmt->getColumnMeta ($i));
}

pdo_cassandra_done ($db, $keyspace);

echo "OK";
--EXPECTF--
array(5) {
  ["native_type"]=>
  string(7) "unknown"
  ["name"]=>
  string(6) "my_key"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(2)
}
array(9) {
  ["native_type"]=>
  string(41) "org.apache.cassandra.db.marshal.BytesType"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(8) "my_bytea"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(2)
}
array(9) {
  ["native_type"]=>
  string(41) "org.apache.cassandra.db.marshal.AsciiType"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(8) "my_ascii"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(2)
}
array(9) {
  ["native_type"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(7) "my_text"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(2)
}
array(9) {
  ["native_type"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(10) "my_varchar"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(2)
}
array(9) {
  ["native_type"]=>
  string(40) "org.apache.cassandra.db.marshal.UUIDType"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(7) "my_uuid"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(2)
}
array(9) {
  ["native_type"]=>
  string(43) "org.apache.cassandra.db.marshal.IntegerType"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(9) "my_varint"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(1)
}
array(9) {
  ["native_type"]=>
  string(40) "org.apache.cassandra.db.marshal.LongType"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(6) "my_int"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(1)
}
array(9) {
  ["native_type"]=>
  string(40) "org.apache.cassandra.db.marshal.LongType"
  ["comparator"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["default_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_validation_class"]=>
  string(40) "org.apache.cassandra.db.marshal.UTF8Type"
  ["key_alias"]=>
  string(6) "my_key"
  ["name"]=>
  string(9) "my_bigint"
  ["len"]=>
  int(-1)
  ["precision"]=>
  int(0)
  ["pdo_type"]=>
  int(1)
}
OK