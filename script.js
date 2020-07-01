'use strict';

var showCompletedCheckbox = document.querySelector('.show-completed');

if (showCompletedCheckbox) {
  showCompletedCheckbox.addEventListener('change', handleCheckboxChange);
}

var taskCheckboxes = document.querySelectorAll('.checkbox__input.task__checkbox');

if (taskCheckboxes.length) {
  taskCheckboxes.forEach(function (taskCheckbox) {
    taskCheckbox.addEventListener('change', handleCheckboxChange);
  });
}

function handleCheckboxChange(evt) {
  if (
      evt.target.tagName !== 'INPUT' ||
      evt.target.getAttribute('type') !== 'checkbox'
  ) {
    throw new Error('Element is not a checkbox input.');
  }

  var isChecked = evt.target.checked;
  var isToggleAction = evt.target.dataset.toggle;
  var name = evt.target.name;
  var value = evt.target.value;

  if (!name || !value) {
    throw new Error('Cannot get parameter name or value for checkbox change handling.');
  }

  var searchParams = new URLSearchParams(window.location.search);

  if (isChecked || isToggleAction) {
    searchParams.set(name, value);
  } else {
    searchParams.delete(name);
  }

  if (Array.from(searchParams).length) {
    window.location = window.location.pathname + '?' + searchParams.toString();
  } else {
    window.location = window.location.pathname;
  }
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
