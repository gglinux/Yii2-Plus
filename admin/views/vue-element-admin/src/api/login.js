import fetch from 'utils/fetch';

export function loginByEmail(email, password) {
  const data = {
    email,
    password
  };
  console.log(data);
  return fetch({
    url: 'admin/user/loginbyemail',
    method: 'post',
    data
  });
}

export function logout() {
  return fetch({
    url: 'admin/user/logout',
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

