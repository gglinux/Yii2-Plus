import fetch from 'utils/fetch';

export function loginByEmail(email, password) {
  const data = {
    email,
    password
  };
  return fetch({
    url: 'admin/login/loginbyemail',
    method: 'post',
    data
  });
}

export function logout() {
  return fetch({
    url: 'admin/login/logout',
    method: 'post'
  });
}

export function getInfo(token) {
  return fetch({
    url: 'admin/user/info',
    method: 'get',
    params: { token }
  });
}

