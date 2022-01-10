// module.exports = {
//     apps: [
//         {
//             name: 'echo',
//             script: 'laravel-echo-server',
//             instances: 1,
//             exec_mode: 'fork',
//             interpreter: 'node',
//             args: 'start'
//         },
//         {
//             name: 'queue',
//             script: 'artisan',
//             exec_mode: 'fork',
//             interpreter: 'php',
//             instances: 1,
//             args: 'queue:work --tries=5 --sleep=1'
//         },
//         {
//             name: 'schedule',
//             script: 'artisan',
//             exec_mode: 'fork',
//             interpreter: 'php',
//             instances: 1,
//             args: 'schedule:work'
//         }
//     ]
// };
