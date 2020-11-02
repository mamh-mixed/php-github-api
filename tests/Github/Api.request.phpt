<?php

/**
 * @author  Miloslav Hůla
 */

declare(strict_types=1);

require __DIR__ . '/../bootstrap.php';


class MockIClient implements Milo\Github\Http\IClient
{
	/** @var callable */
	public $onRequest;

	/** @return Milo\Github\Http\Response */
	public function request(Milo\Github\Http\Request $request): Milo\Github\Http\Response
	{
		return call_user_func($this->onRequest, $request);
	}

	public function onRequest(?callable $cb) {}
	public function onResponse(?callable $cb) {}
}


$client = new MockIClient;
$api = new Milo\Github\Api($client);
$request = new Milo\Github\Http\Request('', '', ['Foo' => 'bar']);


# Default headers, no token
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::true($request->hasHeader('Accept'));
	Assert::true($request->hasHeader('Time-Zone'));
	Assert::true($request->hasHeader('User-Agent'));
	Assert::true($request->hasHeader('Foo'));
	Assert::false($request->hasHeader('Authorization'));
	return new Milo\Github\Http\Response(0, [], '');
};
$api->request($request);


# With token
$token = new Milo\Github\OAuth\Token('hash', 'type', []);
$api->setToken($token);
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::true($request->hasHeader('Authorization'));
	return new Milo\Github\Http\Response(0, [], '');
};
$api->request($request);


# Without token again
$api->setToken(null);
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::false($request->hasHeader('Authorization'));
	return new Milo\Github\Http\Response(0, [], '');
};
$api->request($request);


# DELETE
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::same('DELETE', $request->getMethod());
	Assert::same('https://api.github.com/url?a=b', $request->getUrl());
	Assert::same('bar', $request->getHeader('Foo'));
	return new Milo\Github\Http\Response(0, [], '');
};
$api->delete('/url', ['a' => 'b'], ['Foo' => 'bar']);


# GET
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::same('GET', $request->getMethod());
	Assert::same('https://api.github.com/url?a=b', $request->getUrl());
	Assert::same('bar', $request->getHeader('Foo'));
	return new Milo\Github\Http\Response(0, [], '');
};
$api->get('/url', ['a' => 'b'], ['Foo' => 'bar']);


# HEAD
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::same('HEAD', $request->getMethod());
	Assert::same('https://api.github.com/url?a=b', $request->getUrl());
	Assert::same('bar', $request->getHeader('Foo'));
	return new Milo\Github\Http\Response(0, [], '');
};
$api->head('/url', ['a' => 'b'], ['Foo' => 'bar']);


# PATCH
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::same('PATCH', $request->getMethod());
	Assert::same('https://api.github.com/url?a=b', $request->getUrl());
	Assert::same('bar', $request->getHeader('Foo'));
	Assert::same('{content}', $request->getContent());
	return new Milo\Github\Http\Response(0, [], '');
};
$api->patch('/url', '{content}', ['a' => 'b'], ['Foo' => 'bar']);


# POST
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::same('POST', $request->getMethod());
	Assert::same('https://api.github.com/url?a=b', $request->getUrl());
	Assert::same('bar', $request->getHeader('Foo'));
	Assert::same('{content}', $request->getContent());
	return new Milo\Github\Http\Response(0, [], '');
};
$api->post('/url', '{content}', ['a' => 'b'], ['Foo' => 'bar']);


# PUT
$client->onRequest = function(Milo\Github\Http\Request $request) {
	Assert::same('PUT', $request->getMethod());
	Assert::same('https://api.github.com/url?a=b', $request->getUrl());
	Assert::same('bar', $request->getHeader('Foo'));
	Assert::same('{content}', $request->getContent());
	return new Milo\Github\Http\Response(0, [], '');
};
$api->put('/url', '{content}', ['a' => 'b'], ['Foo' => 'bar']);


Assert::same(28, Assert::$counter);
