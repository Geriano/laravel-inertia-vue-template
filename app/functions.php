<?php

if (!function_exists('flash')) {
  /**
   * @return object
   */
  function flash(int|null $timer = 5000) {
    return new class($timer) {
      public function __construct(
        protected int|null $timer
      ) {}
      
      private function set(string $type, string $format, ...$args) {
        $data = [
          'type' => $type,
          'text' => sprintf($format, ...$args),
          'timer' => $this->timer,
        ];

        if ($flashes = session('flash')) {
          $flashes[] = $data;

          session()->flash('flash', $flashes);
        } else {
          session()->flash('flash', [ $data ]);
        }
      }
      
      public function success(string $format, ...$args) {
        return $this->set('success', $format, ...$args);
      }
      
      public function warning(string $format, ...$args) {
        return $this->set('warning', $format, ...$args);
      }
      
      public function info(string $format, ...$args) {
        return $this->set('info', $format, ...$args);
      }
      
      public function error(string $format, ...$args) {
        $this->timer = null;

        return $this->set('error', $format, ...$args);
      }
    };
  }
}