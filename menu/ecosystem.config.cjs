module.exports = {
	apps: [
		{
			name: 'menus',
			script: '.output/server/index.mjs',
			env: {
				NODE_ENV: 'production',
				PORT: 3002
			},
		}
	]
};
