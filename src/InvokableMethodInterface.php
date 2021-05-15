<?php

namespace donatj\MockDuck;

interface InvokableMethodInterface {

	/**
	 * Invoke the method with the given arguments
	 *
	 * @internal
	 */
	public function __invoke( ...$args );

}
