<?php namespace Inkwell\Relay
{
	use Dotink\Flourish;

	/**
	 *
	 */
	class Resolver
	{
		/**
		 *
		 */
		public function __construct($broker)
		{
			$this->broker = $broker;
		}


		/**
		 *
		 */
		public function __invoke($class)
		{
			if (is_string($class) && class_exists($class)) {
				return $this->broker->make($class);

			} elseif (is_callable($class)) {
				return $class;

			} else {
				throw new Flourish\ProgrammerException(
					'Invalid middleware %s registered for use'
				);
			}
		}
	}
}
